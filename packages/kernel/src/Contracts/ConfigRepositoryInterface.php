<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un repository de configuration.
 */
interface ConfigRepositoryInterface
{
    /**
     * Retourne une valeur de configuration.
     *
     * Exemple :
     * - app.name
     * - database.connections.mysql.host
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Définit une valeur de configuration.
     *
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void;

    /**
     * Vérifie si une clé existe.
     */
    public function has(string $key): bool;

    /**
     * Retourne toute la configuration sous forme de tableau.
     *
     * @return array<mixed>
     */
    public function all(): array;
}