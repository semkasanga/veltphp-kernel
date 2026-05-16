<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use RuntimeException;
use Velt\Kernel\Contracts\ContainerInterface;

final class FakeContainer implements ContainerInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $items = [];

    public function bind(string $id, callable|string $resolver): void
    {
        $this->items[$id] = $resolver;
    }

    public function singleton(string $id, callable|string $resolver): void
    {
        $this->items[$id] = $resolver;
    }

    public function instance(string $id, object $instance): void
    {
        $this->items[$id] = $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->items[$id]);
    }

    public function get(string $id): mixed
    {
        if (! isset($this->items[$id])) {
            throw new RuntimeException("Service not found.");
        }

        return $this->items[$id];
    }
}