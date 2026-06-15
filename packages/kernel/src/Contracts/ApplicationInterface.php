<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

interface ApplicationInterface extends RuntimeInterface
{
    /**
     * Retourne le chemin racine de l'application.
     */
    public function basePath(): string;

    /**
     * Retourne la version du kernel.
     */
    public function version(): string;

    /**
     * Retourne le conteneur principal.
     */
    public function container(): ContainerInterface;

    /**
     * Retourne le repository de configuration.
     */
    public function config(): ConfigRepositoryInterface;

    /**
     * Retourne le dispatcher d'événements.
     */
    public function events(): EventDispatcherInterface;

    /**
     * Retourne le repository d'environnement.
     */
    public function env(): EnvRepositoryInterface;

    /**
     * Retourne le gestionnaire d'exceptions.
     */
    public function exceptions(): ExceptionHandlerInterface;

    /**
     * Retourne l'environnement courant.
     */
    public function environment(): string;

    /**
     * Vérifie si l'application est en environnement local.
     */
    public function isLocal(): bool;

    /**
     * Vérifie si l'application est en environnement de test.
     */
    public function isTesting(): bool;

    /**
     * Vérifie si l'application est en environnement de production.
     */
    public function isProduction(): bool;

    /**
     * Vérifie si le mode debug est activé.
     */
    public function isDebug(): bool;

    /**
     * Enregistre un service provider.
     *
     * @param class-string<ServiceProviderInterface>|ServiceProviderInterface $provider
     */
    public function registerProvider(
        string|ServiceProviderInterface $provider
    ): ServiceProviderInterface;

    /**
     * Vérifie si un provider est enregistré.
     *
     * @param class-string<ServiceProviderInterface> $provider
     */
    public function hasProvider(
        string $provider
    ): bool;

    /**
     * Retourne un provider enregistré.
     *
     * @param class-string<ServiceProviderInterface> $provider
     */
    public function getProvider(
        string $provider
    ): ?ServiceProviderInterface;

    /**
     * Retourne tous les providers enregistrés.
     *
     * @return array<int, ServiceProviderInterface>
     */
    public function providers(): array;

    /**
     * Prépare l'application avant son exécution.
     *
     * Cette étape permet de finaliser le bootstrap
     * du runtime avant le traitement principal.
     */
    public function bootstrap(): void;

    /**
     * Boot tous les providers enregistrés.
     */
    public function boot(): void;

    /**
     * Exécute le runtime courant.
     *
     * Le type d'entrée dépend du contexte :
     * - HTTP : Request
     * - CLI : Command/Input
     * - Desktop : Event
     * - Mobile : Payload
     * - Worker : Job
     */
    public function handle(
        mixed $input = null
    ): mixed;

    /**
     * Termine proprement l'exécution du runtime.
     *
     * Permet de déclencher les hooks de nettoyage,
     * flush des ressources, logs, événements, etc.
     */
    public function terminate(
        mixed $input = null,
        mixed $output = null
    ): void;

    /**
     * Indique si l'application est bootée.
     */
    public function isBooted(): bool;

    /**
     * Indique si l'application est bootstrapée.
     */
    public function isBootstrapped(): bool;

    /**
     * Indique si l'application est terminée.
     */
    public function isTerminated(): bool;
}
