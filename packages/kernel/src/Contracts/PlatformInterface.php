<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Base contract for UI-oriented runtimes such as desktop and mobile.
 */
interface PlatformInterface extends RuntimeInterface
{
    /**
     * Returns the platform name.
     */
    public function name(): string;

    /**
     * Returns the platform capabilities.
     *
     * @return array<string, mixed>
     */
    public function capabilities(): array;
}
