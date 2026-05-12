<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use RuntimeException;
use Velt\Kernel\Contracts\ContainerInterface;

final class FakeContainer implements ContainerInterface
{
    private array $bindings = [];

    public function bind(string $abstract, mixed $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, mixed $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $abstract): mixed
    {
        if (! $this->has($abstract)) {
            throw new RuntimeException(
                "Nothing bound in container for key [{$abstract}]."
            );
        }

        return $this->bindings[$abstract];
    }

    public function has(string $abstract): bool
    {
        return array_key_exists($abstract, $this->bindings);
    }
}