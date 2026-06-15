<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Represents a database driver abstraction.
 */
interface DriverInterface
{
    /**
     * Returns the driver name.
     */
    public function name(): string;

    /**
     * Creates a connection from the given configuration.
     *
     * @param array<string, mixed> $config
     */
    public function connection(
        array $config = []
    ): ConnectionInterface;
}
