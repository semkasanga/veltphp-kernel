<?php

declare(strict_types=1);

namespace Velt\Kernel;

use InvalidArgumentException;
use Velt\Kernel\Config\ConfigRepository;
use Velt\Kernel\Contracts\ApplicationInterface;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Contracts\ServiceProviderInterface;

final class Application implements ApplicationInterface
{
    public const VERSION = '0.1.0';

    private string $basePath;

    private ContainerInterface $container;

    private ConfigRepositoryInterface $config;

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
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR);

        $this->container = new Container();

        $this->config = new ConfigRepository($config);

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

    public function environment(): string
    {
        return $this->config->get('app.env', 'production');
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
    }

    private function registerBaseBindings(): void
    {
        $this->container->instance('app', $this);

        $this->container->instance('config', $this->config);
    }
}