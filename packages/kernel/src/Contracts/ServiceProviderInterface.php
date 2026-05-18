<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un service provider Velt.
 */
interface ServiceProviderInterface
{
    /**
     * Enregistre les services dans le container.
     */
    public function register(): void;

    /**
     * Démarre les services après l'enregistrement.
     */
    public function boot(): void;
}