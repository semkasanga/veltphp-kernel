<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;
use Velt\Kernel\Tests\Fixtures\FakeServiceProvider;
use Velt\Kernel\ServiceProvider;
use Velt\Kernel\Contracts\ApplicationInterface;

final class ApplicationLifecycleTest extends TestCase
{
    protected function setUp(): void
    {
        FakeServiceProvider::$events = [];
        ProviderLifecycleLog::reset();
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

    public function test_provider_without_contract_is_rejected(): void
    {
        $app = new Application(__DIR__);

        $this->expectException(
            InvalidArgumentException::class
        );

        $app->registerProvider(
            NotAServiceProvider::class
        );
    }

    public function test_abstract_provider_is_rejected(): void
    {
        $app = new Application(__DIR__);

        $this->expectException(
            InvalidArgumentException::class
        );

        $app->registerProvider(
            AbstractLifecycleProvider::class
        );
    }

    public function test_provider_with_incompatible_constructor_is_rejected(): void
    {
        $app = new Application(__DIR__);

        $this->expectException(
            InvalidArgumentException::class
        );

        $app->registerProvider(
            BrokenConstructorProvider::class
        );
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

    public function test_has_provider_returns_true_when_registered(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $this->assertTrue(
            $app->hasProvider(
                FakeServiceProvider::class
            )
        );
    }

    public function test_has_provider_returns_false_when_not_registered(): void
    {
        $app = new Application(__DIR__);

        $this->assertFalse(
            $app->hasProvider(
                FakeServiceProvider::class
            )
        );
    }

    public function test_get_provider_returns_registered_provider(): void
    {
        $app = new Application(__DIR__);

        $provider = $app->registerProvider(
            FakeServiceProvider::class
        );

        $this->assertSame(
            $provider,
            $app->getProvider(
                FakeServiceProvider::class
            )
        );
    }

    public function test_get_provider_returns_null_when_missing(): void
    {
        $app = new Application(__DIR__);

        $this->assertNull(
            $app->getProvider(
                FakeServiceProvider::class
            )
        );
    }

    public function test_providers_returns_all_registered_providers(): void
    {
        $app = new Application(__DIR__);

        $provider = $app->registerProvider(
            FakeServiceProvider::class
        );

        $providers = $app->providers();

        $this->assertCount(
            1,
            $providers
        );

        $this->assertSame(
            $provider,
            $providers[0]
        );
    }

    public function test_registering_same_provider_twice_keeps_single_instance(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $app->registerProvider(
            FakeServiceProvider::class
        );

        $this->assertCount(
            1,
            $app->providers()
        );
    }

    public function test_same_provider_is_registered_only_once(): void
    {
        $app = new Application(__DIR__);

        $first = $app->registerProvider(
            FakeServiceProvider::class
        );

        $second = $app->registerProvider(
            FakeServiceProvider::class
        );

        $this->assertSame(
            $first,
            $second
        );

        $this->assertCount(
            1,
            $app->providers()
        );
    }

    public function test_application_lifecycle_flags_change(): void
    {
        $app = new Application(__DIR__);

        $this->assertFalse(
            $app->isBooted()
        );

        $this->assertFalse(
            $app->isBootstrapped()
        );

        $this->assertFalse(
            $app->isTerminated()
        );

        $app->bootstrap();

        $this->assertTrue(
            $app->isBooted()
        );

        $this->assertTrue(
            $app->isBootstrapped()
        );

        $app->terminate();

        $this->assertTrue(
            $app->isTerminated()
        );
    }

    public function test_application_lifecycle_states(): void
    {
        $app = new Application(__DIR__);

        $this->assertFalse(
            $app->isBooted()
        );

        $this->assertFalse(
            $app->isBootstrapped()
        );

        $this->assertFalse(
            $app->isTerminated()
        );

        $app->bootstrap();

        $this->assertTrue(
            $app->isBooted()
        );

        $this->assertTrue(
            $app->isBootstrapped()
        );

        $this->assertFalse(
            $app->isTerminated()
        );

        $app->terminate();

        $this->assertTrue(
            $app->isTerminated()
        );
    }

    public function test_application_exposes_version(): void
    {
        $app = new Application(__DIR__);

        $this->assertSame(
            Application::VERSION,
            $app->version()
        );
    }

    public function test_providers_are_returned_and_booted_in_registration_order(): void
    {
        $app = new Application(__DIR__);

        $app->registerProvider(
            OrderedFirstServiceProvider::class
        );

        $app->registerProvider(
            OrderedSecondServiceProvider::class
        );

        $this->assertSame(
            [
                OrderedFirstServiceProvider::class,
                OrderedSecondServiceProvider::class,
            ],
            array_map(
                static fn ($provider): string => get_class($provider),
                $app->providers()
            )
        );

        $app->boot();

        $this->assertSame(
            [
                'first.register',
                'second.register',
                'first.boot',
                'second.boot',
            ],
            ProviderLifecycleLog::$events
        );
    }

    public function test_terminate_is_idempotent(): void
    {
        $app = new Application(__DIR__);

        $terminatedEvents = 0;

        $app->events()->listen(
            'application.terminated',
            static function () use (&$terminatedEvents): void {
                $terminatedEvents++;
            }
        );

        $app->terminate(
            'input',
            'output'
        );

        $app->terminate(
            'input',
            'output'
        );

        $this->assertSame(
            1,
            $terminatedEvents
        );

        $this->assertTrue(
            $app->isTerminated()
        );
    }
}

final class ProviderLifecycleLog
{
    /**
     * @var array<int, string>
     */
    public static array $events = [];

    public static function reset(): void
    {
        self::$events = [];
    }
}

final class OrderedFirstServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ProviderLifecycleLog::$events[] = 'first.register';
    }

    public function boot(): void
    {
        ProviderLifecycleLog::$events[] = 'first.boot';
    }
}

final class OrderedSecondServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ProviderLifecycleLog::$events[] = 'second.register';
    }

    public function boot(): void
    {
        ProviderLifecycleLog::$events[] = 'second.boot';
    }
}

abstract class AbstractLifecycleProvider extends ServiceProvider
{
}

final class BrokenConstructorProvider extends ServiceProvider
{
    public function __construct(
        ApplicationInterface $app,
        string $name
    ) {
        parent::__construct($app);
    }
}

final class NotAServiceProvider
{
    public function __construct(
        ApplicationInterface $app
    ) {
    }
}
