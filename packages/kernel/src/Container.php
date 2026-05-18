<?php

declare(strict_types=1);

namespace Velt\Kernel;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Exceptions\ServiceNotFoundException;
use Velt\Kernel\Exceptions\ContainerResolutionException;

final class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];
    private array $singletons = [];

    public function bind(string $id, callable|string $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    public function singleton(string $id, callable|string $resolver): void
    {
        $this->singletons[$id] = true;
        $this->bindings[$id] = $resolver;
    }

    public function instance(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
        $this->bindings[$id] = $instance;
        $this->singletons[$id] = true;
    }

    public function has(string $id): bool
    {
        $id = $this->aliases[$id] ?? $id;

        return isset($this->bindings[$id])
            || isset($this->instances[$id])
            || class_exists($id);
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            $service = $this->resolve($this->bindings[$id]);

            if (isset($this->singletons[$id])) {
                $this->instances[$id] = $service;
            }

            return $service;
        }

        if (class_exists($id)) {
            return $this->build($id);
        }

        throw new ServiceNotFoundException("Service not found: {$id}");
    }

    private function resolve(callable|string $resolver): mixed
    {
        if (is_callable($resolver)) {
            return $resolver($this);
        }

        if (class_exists($resolver)) {
            return $this->build($resolver);
        }

        return $resolver;
    }

    private function build(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            throw new ContainerResolutionException(
                "Class {$class} is not instantiable."
            );
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->resolveParameter($class, $parameter);
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveParameter(string $class, ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();

        if (! $type instanceof ReflectionNamedType) {
            throw new ContainerResolutionException(
                "Unable to resolve parameter \${$parameter->getName()} in {$class}"
            );
        }

        if ($type->isBuiltin()) {
            throw new ContainerResolutionException(
                "Cannot resolve scalar parameter \${$parameter->getName()} in {$class}"
            );
        }

        return $this->get($type->getName());
    }
}