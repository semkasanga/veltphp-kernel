<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Velt\Kernel\Contracts\ConfigRepositoryInterface;

final class FakeConfigRepository implements ConfigRepositoryInterface
{
    private array $items = [
        'app.name' => 'Velt',
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->items[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function all(): array
    {
        return $this->items;
    }
}