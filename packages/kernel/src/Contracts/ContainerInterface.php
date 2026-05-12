<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un container de services.
 */
interface ContainerInterface
{
    /**
     * Lie une abstraction dans le container.
     *
     * @param mixed $concrete
     */
    public function bind(string $abstract, mixed $concrete): void;

    /**
     * Lie une instance partagée (singleton).
     *
     * @param mixed $concrete
     */
    public function singleton(string $abstract, mixed $concrete): void;

    /**
     * Résout une entrée du container.
     *
     * @return mixed
     */
    public function make(string $abstract): mixed;

    /**
     * Vérifie si une entrée existe dans le container.
     */
    public function has(string $abstract): bool;
}