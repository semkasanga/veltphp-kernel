<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Represents a database connection abstraction.
 */
interface ConnectionInterface
{
    /**
     * Returns the connection name.
     */
    public function name(): string;

    /**
     * Returns the database driver abstraction.
     */
    public function driver(): DriverInterface;

    /**
     * Opens the connection when needed.
     */
    public function connect(): void;

    /**
     * Closes the connection.
     */
    public function disconnect(): void;

    /**
     * Returns true when the connection is open.
     */
    public function isConnected(): bool;
}
