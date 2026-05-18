<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un repository de variables d'environnement.
 */
interface EnvRepositoryInterface
{
    /**
     * Définit une variable d'environnement.
     */
    public function set(
        string $key,
        mixed $value
    ): void;

    /**
     * Récupère une variable d'environnement.
     */
    public function get(
        string $key,
        mixed $default = null
    ): mixed;

    /**
     * Vérifie si une variable existe.
     */
    public function has(string $key): bool;

    /**
     * Charge un fichier .env.
     */
    public function load(string $path): void;
}