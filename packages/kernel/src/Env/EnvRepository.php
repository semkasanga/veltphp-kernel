<?php

declare(strict_types=1);

namespace Velt\Kernel\Env;

use RuntimeException;
use Velt\Kernel\Contracts\EnvRepositoryInterface;

final class EnvRepository implements EnvRepositoryInterface
{
    /**
     * Variables d'environnement chargées.
     *
     * @var array<string, mixed>
     */
    private array $variables = [];

    public function set(
        string $key,
        mixed $value
    ): void {
        $this->variables[$key] = $value;
    }

    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->variables[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists(
            $key,
            $this->variables
        );
    }

    public function load(string $path): void
    {
        if (! file_exists($path)) {
            throw new RuntimeException(
                "Environment file not found."
            );
        }

        $lines = file($path);

        if ($lines === false) {
            throw new RuntimeException(
                "Unable to read environment file."
            );
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, '#')) {
                continue;
            }

            if (! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode(
                '=',
                $line,
                2
            );

            $key = trim($key);

            $value = trim($value);

            $this->set(
                $key,
                $this->castValue($value)
            );
        }
    }

    private function castValue(
        string $value
    ): mixed {
        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            'empty' => '',
            default => $value,
        };
    }
}