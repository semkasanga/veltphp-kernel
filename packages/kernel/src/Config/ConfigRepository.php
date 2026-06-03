<?php

declare(strict_types=1);

namespace Velt\Kernel\Config;

use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Contracts\EnvRepositoryInterface;

final class ConfigRepository implements ConfigRepositoryInterface
{
    /**
     * Données de configuration.
     *
     * @var array<string, mixed>
     */
    private array $items = [];

    public function __construct(
        array $items = [],
        private readonly ?EnvRepositoryInterface $env = null
    ) {
        $this->items = $items;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);

        $value = $this->items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                // fallback env si disponible (ex: APP_DEBUG)
                return $this->env?->get($key, $default) ?? $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);

        $current = &$this->items;

        foreach ($segments as $segment) {
            if (!isset($current[$segment]) || !is_array($current[$segment])) {
                $current[$segment] = [];
            }

            $current = &$current[$segment];
        }

        $current = $value;
    }

    public function has(string $key): bool
    {
        $segments = explode('.', $key);

        $value = $this->items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return false;
            }

            $value = $value[$segment];
        }

        return true;
    }

    public function all(): array
    {
        return $this->items;
    }
}