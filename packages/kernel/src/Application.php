<?php

declare(strict_types=1);

namespace Velt\Kernel;

use InvalidArgumentException;
use RuntimeException;
use Throwable;
use Velt\Kernel\Config\ConfigRepository;
use Velt\Kernel\Contracts\ApplicationInterface;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Contracts\EnvRepositoryInterface;
use Velt\Kernel\Contracts\EventDispatcherInterface;
use Velt\Kernel\Contracts\ExceptionHandlerInterface;
use Velt\Kernel\Contracts\ServiceProviderInterface;
use Velt\Kernel\Env\EnvRepository;
use Velt\Kernel\Exceptions\DefaultExceptionHandler;

final class Application implements ApplicationInterface
{
    public const VERSION = '0.1.0';

    private string $basePath;

    public function version(): string
    {
        return self::VERSION;
    }

    private ContainerInterface $container;

    private ConfigRepositoryInterface $config;

    private EventDispatcherInterface $events;

    private EnvRepositoryInterface $env;

    private ExceptionHandlerInterface $exceptions;

    /**
     * @var array<class-string, ServiceProviderInterface>
     */
    private array $providers = [];

    private bool $booted = false;

    private bool $bootstrapped = false;

    private bool $terminated = false;

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

        $this->env = new EnvRepository();

        $this->loadEnvironment();

        $configuration = $this->loadConfigurationFiles();

        $this->config = new ConfigRepository(
            $this->mergeConfiguration(
                $configuration,
                $config
            ),
            $this->env
        );

        $this->events = new EventDispatcher();

        $this->exceptions = new DefaultExceptionHandler(
            $this->isDebug()
        );

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

    public function exceptions(): ExceptionHandlerInterface
    {
        return $this->exceptions;
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
            if (isset($this->providers[$provider])) {
                return $this->providers[$provider];
            }

            if (! class_exists($provider)) {
                throw new InvalidArgumentException(
                    "Provider class [$provider] does not exist."
                );
            }

            $instance = $this->instantiateProvider(
                $provider
            );

            if (! $instance instanceof ServiceProviderInterface) {
                throw new InvalidArgumentException(
                    "Provider class [$provider] must implement ServiceProviderInterface."
                );
            }

            $provider = $instance;
        }

        if (isset($this->providers[$provider::class])) {
            return $this->providers[$provider::class];
        }

        $provider->register();

        $this->providers[$provider::class] = $provider;

        $this->events->dispatch(
            'provider.registered',
            $provider
        );

        return $provider;
    }

    public function hasProvider(
        string $provider
    ): bool {
        return isset(
            $this->providers[$provider]
        );
    }

    public function getProvider(
        string $provider
    ): ?ServiceProviderInterface {
        return $this->providers[$provider]
            ?? null;
    }

    /**
     * @return array<int, ServiceProviderInterface>
     */
    public function providers(): array
    {
        return array_values(
            $this->providers
        );
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function isBootstrapped(): bool
    {
        return $this->bootstrapped;
    }

    public function isTerminated(): bool
    {
        return $this->terminated;
    }

    /**
     * Prepare l'application avant execution.
     */
    public function bootstrap(): void
    {
        if ($this->bootstrapped) {
            return;
        }

        $this->events->dispatch(
            'application.bootstrapping'
        );

        $this->boot();

        $this->bootstrapped = true;

        $this->events->dispatch(
            'application.bootstrapped'
        );
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

    /**
     * Point d'entree runtime.
     */
    public function handle(
        mixed $input = null
    ): mixed {
        $this->bootstrap();

        $this->events->dispatch(
            'application.handling',
            $input
        );

        $output = $input;

        $this->events->dispatch(
            'application.handled',
            $output
        );

        return $output;
    }

    /**
     * Termine proprement l'execution.
     */
    public function terminate(
        mixed $input = null,
        mixed $output = null
    ): void {
        if ($this->terminated) {
            return;
        }

        $this->events->dispatch(
            'application.terminating',
            [
                'input' => $input,
                'output' => $output,
            ]
        );

        $this->terminated = true;

        $this->events->dispatch(
            'application.terminated',
            [
                'input' => $input,
                'output' => $output,
            ]
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

        $this->container->instance(
            'exceptions',
            $this->exceptions
        );

        $this->container->instance(
            ApplicationInterface::class,
            $this
        );

        $this->container->instance(
            ContainerInterface::class,
            $this->container
        );

        $this->container->instance(
            ConfigRepositoryInterface::class,
            $this->config
        );

        $this->container->instance(
            EnvRepositoryInterface::class,
            $this->env
        );

        $this->container->instance(
            EventDispatcherInterface::class,
            $this->events
        );

        $this->container->instance(
            ExceptionHandlerInterface::class,
            $this->exceptions
        );
    }

    /**
     * Charge config/*.php.
     *
     * @return array<string, mixed>
     */
    private function loadConfigurationFiles(): array
    {
        $configPath = $this->basePath
            . DIRECTORY_SEPARATOR
            . 'config';

        if (! is_dir($configPath)) {
            return [];
        }

        $configuration = [];

        $files = glob(
            $configPath
            . DIRECTORY_SEPARATOR
            . '*.php'
        ) ?: [];

        sort($files, SORT_NATURAL);

        foreach ($files as $file) {
            if (! is_file($file)) {
                continue;
            }

            $key = basename(
                $file,
                '.php'
            );

            $data = require $file;

            if (! is_array($data)) {
                throw new RuntimeException(
                    sprintf(
                        'Configuration file [%s] must return an array.',
                        $file
                    )
                );
            }

            $configuration[$key] = $this->mergeConfiguration(
                $configuration[$key] ?? [],
                $data
            );
        }

        return $configuration;
    }

    /**
     * @param array<int|string, mixed> $base
     * @param array<int|string, mixed> $overrides
     *
     * @return array<int|string, mixed>
     */
    private function mergeConfiguration(
        array $base,
        array $overrides
    ): array {
        foreach ($overrides as $key => $value) {
            if (
                array_key_exists($key, $base) &&
                is_array($base[$key]) &&
                is_array($value) &&
                $this->canMergeConfigurationArrays(
                    $base[$key],
                    $value
                )
            ) {
                $base[$key] = $this->mergeConfiguration(
                    $base[$key],
                    $value
                );

                continue;
            }

            $base[$key] = $value;
        }

        return $base;
    }

    /**
     * @param array<int|string, mixed> $left
     * @param array<int|string, mixed> $right
     */
    private function canMergeConfigurationArrays(
        array $left,
        array $right
    ): bool {
        return (
            (
                $left === [] ||
                ! array_is_list($left)
            ) &&
            (
                $right === [] ||
                ! array_is_list($right)
            )
        );
    }

    private function loadEnvironment(): void
    {
        $envPath = $this->basePath
            . DIRECTORY_SEPARATOR
            . '.env';

        if (! file_exists($envPath)) {
            return;
        }

        $this->env->load($envPath);
    }

    private function instantiateProvider(
        string $provider
    ): object {
        try {
            return new $provider($this);
        } catch (Throwable $exception) {
            throw new InvalidArgumentException(
                "Provider class [$provider] could not be instantiated.",
                0,
                $exception
            );
        }
    }
}
