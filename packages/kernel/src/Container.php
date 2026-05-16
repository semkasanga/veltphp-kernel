<?php

declare(strict_types=1);

namespace Velt\Kernel;

use Velt\Kernel\Exceptions\ServiceNotFoundException;
use Velt\Kernel\Contracts\ContainerInterface;

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
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * Services marqués comme singleton.
     *
     * @var array<string, bool>
     */
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
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (! isset($this->bindings[$id])) {
            throw new ServiceNotFoundException(
                "Service not found: {$id}"
            );
        }

        $resolver = $this->bindings[$id];

        if (
            isset($this->singletons[$id]) &&
            isset($this->instances[$id])
        ) {
            return $this->instances[$id];
        }

        $service = is_callable($resolver)
            ? $resolver()
            : $resolver;

        if (isset($this->singletons[$id])) {
            $this->instances[$id] = $service;
        }

        return $service;
    }
}
