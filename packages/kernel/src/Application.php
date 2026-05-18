<?php

declare(strict_types=1);

namespace Velt\Kernel;

use InvalidArgumentException;
use Velt\Kernel\Env\EnvRepository;
use Velt\Kernel\Config\ConfigRepository;
use Velt\Kernel\Contracts\ApplicationInterface;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Contracts\EnvRepositoryInterface;
use Velt\Kernel\Contracts\EventDispatcherInterface;
use Velt\Kernel\Contracts\ServiceProviderInterface;

final class Application implements ApplicationInterface
{
    public const VERSION = '0.1.0';

    private string $basePath;

    private ContainerInterface $container;

    private ConfigRepositoryInterface $config;

    private EventDispatcherInterface $events;

    private EnvRepositoryInterface $env;

    /**
     * Providers enregistrés.
     *
     * @var array<int, ServiceProviderInterface>
     */
    private array $providers = [];

    /**
     * Indique si l'application a été bootée.
     */
    private bool $booted = false;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        string $basePath,
        array $config = []
    ) {
        $this->basePath = rtrim(
            $basePath,
            DIRECTORY_SEPARATOR
        );

        $this->container = new Container();

        $this->config = new ConfigRepository(
            $config
        );

        $this->events = new EventDispatcher();

        $this->env = new EnvRepository();

        $this->loadEnvironment();

        $this->registerBaseBindings();
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public function config(): ConfigRepositoryInterface
    {
        return $this->config;
    }

    public function events(): EventDispatcherInterface
    {
        return $this->events;
    }

    public function env(): EnvRepositoryInterface
    {
        return $this->env;
    }

    public function environment(): string
    {
        return (string) $this->env->get(
            'APP_ENV',
            'production'
        );
    }

    public function isLocal(): bool
    {
        return $this->environment() === 'local';
    }

    public function isProduction(): bool
    {
        return $this->environment() === 'production';
    }

    public function isTesting(): bool
    {
        return $this->environment() === 'testing';
    }

    public function isDebug(): bool
    {
        return (bool) $this->env->get(
            'APP_DEBUG',
            false
        );
    }

    public function registerProvider(
        string|ServiceProviderInterface $provider
    ): ServiceProviderInterface {
        if ($this->booted) {
            throw new InvalidArgumentException(
                'Cannot register provider after application boot.'
            );
        }

        if (is_string($provider)) {
            if (! class_exists($provider)) {
                throw new InvalidArgumentException(
                    "Provider class [$provider] does not exist."
                );
            }

            $provider = new $provider($this);
        }

        if (! $provider instanceof ServiceProviderInterface) {
            throw new InvalidArgumentException(
                'Provider must implement ServiceProviderInterface.'
            );
        }

        $provider->register();

        $this->providers[] = $provider;

        $this->events->dispatch(
            'provider.registered',
            $provider
        );

        return $provider;
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->providers as $provider) {
            $provider->boot();
        }

        $this->booted = true;

        $this->events->dispatch(
            'application.booted'
        );
    }

    private function registerBaseBindings(): void
    {
        $this->container->instance(
            'app',
            $this
        );

        $this->container->instance(
            'config',
            $this->config
        );

        $this->container->instance(
            'events',
            $this->events
        );

        $this->container->instance(
            'env',
            $this->env
        );
    }

    private function loadEnvironment(): void
    {
        $envPath = $this->basePath . DIRECTORY_SEPARATOR . '.env';

        if (! file_exists($envPath)) {
            return;
        }

        $this->env->load($envPath);
    }
}