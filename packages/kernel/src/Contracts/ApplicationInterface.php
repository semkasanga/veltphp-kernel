<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente l'application Velt en cours d'exécution.
 */
interface ApplicationInterface
{
    /**
     * Retourne le chemin racine du projet.
     */
    public function basePath(): string;

    /**
     * Retourne le container de services.
     */
    public function container(): ContainerInterface;

    /**
     * Retourne le repository de configuration.
     */
    public function config(): ConfigRepositoryInterface;

    /**
     * Retourne l'environnement courant.
     *
     * Exemple :
     * - local
     * - testing
     * - production
     */
    public function environment(): string;

    /**
     * Vérifie si l'application est en environnement local.
     */
    public function isLocal(): bool;

    /**
     * Vérifie si l'application est en environnement de production.
     */
    public function isProduction(): bool;

    /**
     * Vérifie si l'application est en environnement de test.
     */
    public function isTesting(): bool;
}