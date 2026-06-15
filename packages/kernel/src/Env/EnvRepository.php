<?php

declare(strict_types=1);

namespace Velt\Kernel\Env;

use InvalidArgumentException;
use RuntimeException;
use Velt\Kernel\Contracts\EnvRepositoryInterface;

final class EnvRepository implements EnvRepositoryInterface
{
    /**
     * Variables d'environnement chargees.
     *
     * @var array<string, mixed>
     */
    private array $variables = [];

    public function set(
        string $key,
        mixed $value
    ): void {
        $this->assertValidKey($key);

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
        if (! is_file($path)) {
            throw new RuntimeException(
                'Environment file not found.'
            );
        }

        if (! is_readable($path)) {
            throw new RuntimeException(
                'Unable to read environment file.'
            );
        }

        $lines = file($path);

        if ($lines === false) {
            throw new RuntimeException(
                'Unable to read environment file.'
            );
        }

        $loaded = [];

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (
                str_starts_with($line, '#') ||
                str_starts_with($line, ';')
            ) {
                continue;
            }

            if (! str_contains($line, '=')) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid environment line at %d.',
                        $lineNumber + 1
                    )
                );
            }

            [$key, $value] = explode(
                '=',
                $line,
                2
            );

            $key = trim($key);
            $value = trim($value);

            try {
                $this->assertValidKey($key);
            } catch (InvalidArgumentException $exception) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid environment key [%s] at line %d.',
                        $key,
                        $lineNumber + 1
                    ),
                    0,
                    $exception
                );
            }

            $loaded[$key] = $this->castValue($value);
        }

        // Commit une fois la lecture terminée pour éviter un état partiel.
        foreach ($loaded as $key => $value) {
            $this->variables[$key] = $value;
        }
    }

    private function castValue(
        string $value
    ): mixed {
        $value = $this->stripQuotes($value);

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            'empty' => '',
            default => $value,
        };
    }

    private function stripQuotes(string $value): string
    {
        $length = strlen($value);

        if ($length < 2) {
            return $value;
        }

        $firstCharacter = $value[0];
        $lastCharacter = $value[$length - 1];

        if (
            (
                $firstCharacter === '"' ||
                $firstCharacter === "'"
            ) &&
            $firstCharacter === $lastCharacter
        ) {
            return substr(
                $value,
                1,
                -1
            );
        }

        return $value;
    }

    private function assertValidKey(string $key): void
    {
        if (
            ! preg_match(
                '/^[A-Za-z_][A-Za-z0-9_]*$/',
                $key
            )
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid environment key [%s].',
                    $key
                )
            );
        }
    }
}
