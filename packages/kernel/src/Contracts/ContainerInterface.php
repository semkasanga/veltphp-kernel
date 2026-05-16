<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un container de services.
 */
interface ContainerInterface
{
    /**
     * Lie un service dans le container.
     *
     * @param callable|string $resolver
     */
    public function bind(string $id, callable|string $resolver): void;

    /**
     * Lie un singleton dans le container.
     *
     * @param callable|string $resolver
     */
    public function singleton(string $id, callable|string $resolver): void;

    /**
     * Enregistre une instance existante.
     */
    public function instance(string $id, object $instance): void;

    /**
     * Vérifie si un service existe.
     */
    public function has(string $id): bool;

    /**
     * Résout un service depuis le container.
     *
     * @return mixed
     */
    public function get(string $id): mixed;
}