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

    /**
     * @param array<string, mixed> $items
     */
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
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $this->resolveEnvironmentValue(
                    $key,
                    $default
                );
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
            if (! isset($current[$segment]) || ! is_array($current[$segment])) {
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
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $this->hasEnvironmentValue($key);
            }

            $value = $value[$segment];
        }

        return true;
    }

    public function all(): array
    {
        return $this->items;
    }

    private function resolveEnvironmentValue(
        string $key,
        mixed $default = null
    ): mixed {
        if ($this->env === null) {
            return $default;
        }

        $envKey = $this->normalizeEnvironmentKey($key);

        if (! $this->env->has($envKey)) {
            return $default;
        }

        return $this->env->get(
            $envKey,
            $default
        );
    }

    private function hasEnvironmentValue(string $key): bool
    {
        if ($this->env === null) {
            return false;
        }

        return $this->env->has(
            $this->normalizeEnvironmentKey($key)
        );
    }

    private function normalizeEnvironmentKey(string $key): string
    {
        $normalized = preg_replace(
            '/[^A-Za-z0-9]+/',
            '_',
            $key
        );

        if ($normalized === null) {
            $normalized = $key;
        }

        return strtoupper(
            trim(
                $normalized,
                '_'
            )
        );
    }
}
