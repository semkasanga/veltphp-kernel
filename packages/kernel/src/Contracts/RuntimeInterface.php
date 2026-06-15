<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Represents a generic Kernel runtime.
 */
interface RuntimeInterface
{
    /**
     * Returns the main container.
     */
    public function container(): ContainerInterface;

    /**
     * Returns the event dispatcher.
     */
    public function events(): EventDispatcherInterface;

    /**
     * Prepares the runtime before execution.
     */
    public function bootstrap(): void;

    /**
     * Handles the current runtime input.
     *
     * @return mixed
     */
    public function handle(
        mixed $input = null
    ): mixed;

    /**
     * Terminates the current runtime.
     */
    public function terminate(
        mixed $input = null,
        mixed $output = null
    ): void;

    /**
     * Returns true when the runtime has already been bootstrapped.
     */
    public function isBootstrapped(): bool;

    /**
     * Returns true when the runtime has terminated.
     */
    public function isTerminated(): bool;
}
