<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Represents the database entry point for the Kernel.
 */
interface DatabaseManagerInterface
{
    /**
     * Returns a named connection or the default one.
     */
    public function connection(
        ?string $name = null
    ): ConnectionInterface;

    /**
     * Returns a named driver or the default one.
     */
    public function driver(
        ?string $name = null
    ): DriverInterface;

    /**
     * Returns true when a connection exists.
     */
    public function hasConnection(string $name): bool;

    /**
     * Returns true when a driver exists.
     */
    public function hasDriver(string $name): bool;
}
