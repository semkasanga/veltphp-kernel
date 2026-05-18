<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;
use Velt\Kernel\Config\ConfigRepository;
use Velt\Kernel\Container;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Contracts\EnvRepositoryInterface;
use Velt\Kernel\Contracts\EventDispatcherInterface;

final class ApplicationTest extends TestCase
{
    public function test_application_exposes_version(): void
    {
        $this->assertSame(
            '0.1.0',
            Application::VERSION
        );
    }

    public function test_application_can_be_instantiated(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            Application::class,
            $app
        );
    }

    public function test_application_returns_base_path(): void
    {
        $app = new Application(__DIR__);

        $this->assertSame(
            __DIR__,
            $app->basePath()
        );
    }

    public function test_application_exposes_container(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            ContainerInterface::class,
            $app->container()
        );
    }

    public function test_application_exposes_config_repository(): void
    {
        $app = new Application(
            __DIR__,
            [
                'app' => [
                    'name' => 'Velt',
                ],
            ]
        );

        $this->assertInstanceOf(
            ConfigRepositoryInterface::class,
            $app->config()
        );

        $this->assertSame(
            'Velt',
            $app->config()->get('app.name')
        );
    }

    public function test_application_exposes_event_dispatcher(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            EventDispatcherInterface::class,
            $app->events()
        );
    }

    public function test_application_exposes_env_repository(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            EnvRepositoryInterface::class,
            $app->env()
        );
    }

    public function test_application_detects_local_environment(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-local-env';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            'APP_ENV=local'
        );

        $app = new Application($basePath);

        $this->assertTrue(
            $app->isLocal()
        );

        $this->assertFalse(
            $app->isProduction()
        );

        $this->assertFalse(
            $app->isTesting()
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_application_detects_testing_environment(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-testing-env';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            'APP_ENV=testing'
        );

        $app = new Application($basePath);

        $this->assertTrue(
            $app->isTesting()
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_application_detects_production_environment(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-production-env';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            'APP_ENV=production'
        );

        $app = new Application($basePath);

        $this->assertTrue(
            $app->isProduction()
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_application_detects_debug_mode(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-debug-env';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            "APP_ENV=local\nAPP_DEBUG=true"
        );

        $app = new Application($basePath);

        $this->assertTrue(
            $app->isDebug()
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_application_registers_base_bindings(): void
    {
        $app = new Application(__DIR__);

        $container = $app->container();

        $this->assertSame(
            $app,
            $container->get('app')
        );

        $this->assertInstanceOf(
            ConfigRepository::class,
            $container->get('config')
        );

        $this->assertInstanceOf(
            EventDispatcherInterface::class,
            $container->get('events')
        );

        $this->assertInstanceOf(
            EnvRepositoryInterface::class,
            $container->get('env')
        );
    }
}