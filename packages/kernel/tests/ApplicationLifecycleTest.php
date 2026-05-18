<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;
use Velt\Kernel\Tests\Fixtures\FakeServiceProvider;

final class ApplicationLifecycleTest extends TestCase
{
    protected function setUp(): void
    {
        FakeServiceProvider::$events = [];
    }

    public function test_provider_registers_service(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $this->assertTrue(
            $app->container()->has('fake.service')
        );
    }

    public function test_register_is_called_before_boot(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $app->boot();

        $this->assertSame(
            ['register', 'boot'],
            FakeServiceProvider::$events
        );
    }

    public function test_application_does_not_boot_twice(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $app->boot();

        $app->boot();

        $this->assertSame(
            ['register', 'boot'],
            FakeServiceProvider::$events
        );
    }

    public function test_invalid_provider_throws_exception(): void
    {
        $app = new Application(__DIR__);

        $this->expectException(
            InvalidArgumentException::class
        );

        $app->registerProvider('InvalidProvider');
    }

    public function test_cannot_register_provider_after_boot(): void
    {
        $app = new Application(__DIR__);

        $app->boot();

        $this->expectException(
            InvalidArgumentException::class
        );

        $app->registerProvider(
            FakeServiceProvider::class
        );
    }
}