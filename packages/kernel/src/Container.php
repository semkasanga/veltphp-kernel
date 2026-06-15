<?php

declare(strict_types=1);

namespace Velt\Kernel;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Exceptions\ContainerResolutionException;
use Velt\Kernel\Exceptions\ServiceNotFoundException;

final class Container implements ContainerInterface
{
    /**
     * Services enregistrés.
     *
     * @var array<string, mixed>
     */
    private array $bindings = [];

    /**
     * Instances singleton résolues.
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Services marqués comme singleton.
     *
     * @var array<string, bool>
     */
    private array $singletons = [];

    /**
     * Aliases de services.
     *
     * @var array<string, string>
     */
    private array $aliases = [];

    /**
     * Identifiants en cours de résolution.
     *
     * @var array<int, string>
     */
    private array $resolving = [];

    public function bind(
        string $id,
        callable|string $resolver
    ): void {
        $this->bindings[$id] = $resolver;

        unset(
            $this->instances[$id],
            $this->singletons[$id]
        );
    }

    public function singleton(
        string $id,
        callable|string $resolver
    ): void {
        $this->singletons[$id] = true;

        $this->bindings[$id] = $resolver;

        unset($this->instances[$id]);
    }

    public function instance(
        string $id,
        object $instance
    ): void {
        $this->instances[$id] = $instance;

        $this->bindings[$id] = $instance;

        $this->singletons[$id] = true;
    }

    public function alias(
        string $abstract,
        string $alias
    ): void {
        $this->aliases[$alias] = $abstract;
    }

    public function has(string $id): bool
    {
        try {
            $id = $this->normalizeIdentifier($id);
        } catch (ContainerResolutionException) {
            return false;
        }

        return $this->canResolveIdentifier(
            $id,
            []
        );
    }

    public function get(string $id): mixed
    {
        $id = $this->normalizeIdentifier($id);

        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (array_key_exists($id, $this->bindings)) {
            $this->enterResolution(
                $id,
                'service'
            );

            try {
                $service = $this->resolve($this->bindings[$id]);

                if (array_key_exists($id, $this->singletons)) {
                    $this->instances[$id] = $service;
                }

                return $service;
            } finally {
                $this->leaveResolution();
            }
        }

        if (class_exists($id)) {
            return $this->build($id);
        }

        throw new ServiceNotFoundException(
            "Service not found: {$id}"
        );
    }

    private function resolve(
        callable|string $resolver
    ): mixed {
        if (is_callable($resolver)) {
            return $resolver($this);
        }

        $resolver = $this->normalizeIdentifier($resolver);

        if (
            array_key_exists($resolver, $this->instances) ||
            array_key_exists($resolver, $this->bindings)
        ) {
            return $this->get($resolver);
        }

        if (class_exists($resolver)) {
            return $this->build($resolver);
        }

        throw new ContainerResolutionException(
            "Unable to resolve service [{$resolver}]."
        );
    }

    /**
     * Vérifie si un identifiant peut être résolu sans instancier de service.
     *
     * @param array<string, true> $stack
     */
    private function canResolveIdentifier(
        string $id,
        array $stack
    ): bool {
        $frame = 'id:' . $id;

        if (isset($stack[$frame])) {
            return false;
        }

        $stack[$frame] = true;

        if (array_key_exists($id, $this->instances)) {
            return true;
        }

        if (array_key_exists($id, $this->bindings)) {
            $resolver = $this->bindings[$id];

            if (is_callable($resolver)) {
                return true;
            }

            if (! is_string($resolver)) {
                return false;
            }

            try {
                $resolver = $this->normalizeIdentifier($resolver);
            } catch (ContainerResolutionException) {
                return false;
            }

            if (isset($stack['id:' . $resolver])) {
                return false;
            }

            if (
                array_key_exists($resolver, $this->instances) ||
                array_key_exists($resolver, $this->bindings)
            ) {
                return $this->canResolveIdentifier(
                    $resolver,
                    $stack
                );
            }

            if (! class_exists($resolver)) {
                return false;
            }

            return $this->canResolveClass(
                $resolver,
                $stack
            );
        }

        if (! class_exists($id)) {
            return false;
        }

        return $this->canResolveClass(
            $id,
            $stack
        );
    }

    /**
     * @param array<string, true> $stack
     */
    private function canResolveClass(
        string $class,
        array $stack
    ): bool {
        $frame = 'class:' . $class;

        if (isset($stack[$frame])) {
            return false;
        }

        $stack[$frame] = true;

        $reflection = new ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            return false;
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return true;
        }

        foreach (
            $constructor->getParameters()
            as $parameter
        ) {
            if (! $this->canResolveParameter(
                $class,
                $parameter,
                $stack
            )) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<string, true> $stack
     */
    private function canResolveParameter(
        string $class,
        ReflectionParameter $parameter,
        array $stack
    ): bool {
        if ($parameter->isVariadic()) {
            return false;
        }

        $type = $parameter->getType();

        if ($type === null) {
            return $parameter->isDefaultValueAvailable();
        }

        if (! $type instanceof ReflectionNamedType) {
            return false;
        }

        if ($parameter->isDefaultValueAvailable()) {
            return true;
        }

        if ($type->isBuiltin()) {
            return $type->allowsNull();
        }

        $expectedType = $type->getName();

        if ($expectedType === 'self') {
            $expectedType = $class;
        } elseif ($expectedType === 'parent') {
            $parentClass = get_parent_class($class);

            if ($parentClass === false) {
                return false;
            }

            $expectedType = $parentClass;
        }

        if ($type->allowsNull()) {
            return true;
        }

        return $this->canResolveIdentifier(
            $expectedType,
            $stack
        );
    }

    private function build(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            throw new ContainerResolutionException(
                "Class {$class} is not instantiable."
            );
        }

        $this->enterResolution(
            $class,
            'class'
        );

        try {
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                return new $class();
            }

            $dependencies = [];

            foreach (
                $constructor->getParameters()
                as $parameter
            ) {
                $dependencies[] = $this->resolveParameter(
                    $class,
                    $parameter
                );
            }

            return $reflection->newInstanceArgs(
                $dependencies
            );
        } finally {
            $this->leaveResolution();
        }
    }

    private function resolveParameter(
        string $class,
        ReflectionParameter $parameter
    ): mixed {
        if ($parameter->isVariadic()) {
            throw new ContainerResolutionException(
                "Unable to resolve variadic parameter \${$parameter->getName()} in {$class}."
            );
        }

        $type = $parameter->getType();

        if ($type === null) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new ContainerResolutionException(
                "Unable to resolve untyped parameter \${$parameter->getName()} in {$class}."
            );
        }

        if (! $type instanceof ReflectionNamedType) {
            throw new ContainerResolutionException(
                "Unable to resolve parameter \${$parameter->getName()} in {$class}: union and intersection types are not supported."
            );
        }

        $expectedType = $type->getName();

        if ($expectedType === 'self') {
            $expectedType = $class;
        } elseif ($expectedType === 'parent') {
            $parentClass = get_parent_class($class);

            if ($parentClass === false) {
                throw new ContainerResolutionException(
                    "Unable to resolve parent type for \${$parameter->getName()} in {$class}."
                );
            }

            $expectedType = $parentClass;
        }

        if ($type->isBuiltin()) {
            return $this->resolveBuiltinParameter(
                $class,
                $parameter,
                $type
            );
        }

        try {
            $resolved = $this->get(
                $expectedType
            );
        } catch (ServiceNotFoundException|ContainerResolutionException $exception) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            if ($type->allowsNull()) {
                return null;
            }

            throw new ContainerResolutionException(
                "Unable to resolve dependency \${$parameter->getName()} of type {$type->getName()} in {$class}.",
                0,
                $exception
            );
        }

        if ($resolved === null) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            if ($type->allowsNull()) {
                return null;
            }

            throw new ContainerResolutionException(
                "Resolved dependency \${$parameter->getName()} in {$class} cannot be null."
            );
        }

        if (
            ! is_object($resolved) ||
            ! is_a($resolved, $expectedType)
        ) {
            throw new ContainerResolutionException(
                "Resolved dependency \${$parameter->getName()} in {$class} must be an instance of {$expectedType}."
            );
        }

        return $resolved;
    }

    private function resolveBuiltinParameter(
        string $class,
        ReflectionParameter $parameter,
        ReflectionNamedType $type
    ): mixed {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($type->allowsNull()) {
            return null;
        }

        throw new ContainerResolutionException(
            "Cannot resolve builtin parameter \${$parameter->getName()} in {$class}."
        );
    }

    private function normalizeIdentifier(string $id): string
    {
        $seen = [];

        while (array_key_exists($id, $this->aliases)) {
            if (isset($seen[$id])) {
                $path = array_keys($seen);
                $path[] = $id;

                throw new ContainerResolutionException(
                    'Circular alias detected: ' . implode(
                        ' -> ',
                        $path
                    )
                );
            }

            $seen[$id] = true;

            $id = $this->aliases[$id];
        }

        return $id;
    }

    private function enterResolution(
        string $id,
        string $kind
    ): void
    {
        $frame = $kind . ':' . $id;

        if (in_array($frame, $this->resolving, true)) {
            $path = $this->resolving;
            $path[] = $frame;

            throw new ContainerResolutionException(
                'Circular dependency detected: ' . implode(
                    ' -> ',
                    $path
                )
            );
        }

        $this->resolving[] = $frame;
    }

    private function leaveResolution(): void
    {
        array_pop($this->resolving);
    }
}
