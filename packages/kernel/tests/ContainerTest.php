<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Container;
use Velt\Kernel\Exceptions\ServiceNotFoundException;

final class ContainerTest extends TestCase
{
    public function test_bind_creates_new_instance_each_time(): void
    {
        $container = new Container();

        $container->bind('service', fn () => new \stdClass());

        $first = $container->get('service');
        $second = $container->get('service');

        $this->assertNotSame($first, $second);
    }

    public function test_singleton_returns_same_instance(): void
    {
        $container = new Container();

        $container->singleton('service', fn () => new \stdClass());

        $first = $container->get('service');
        $second = $container->get('service');

        $this->assertSame($first, $second);
    }

    public function test_instance_registers_existing_object(): void
    {
        $container = new Container();

        $object = new \stdClass();

        $container->instance('service', $object);

        $resolved = $container->get('service');

        $this->assertSame($object, $resolved);
    }

    public function test_has_returns_true_when_service_exists(): void
    {
        $container = new Container();

        $container->bind('service', fn () => new \stdClass());

        $this->assertTrue($container->has('service'));
    }

    public function test_has_returns_false_when_service_does_not_exist(): void
    {
        $container = new Container();

        $this->assertFalse($container->has('unknown'));
    }

    public function test_get_throws_exception_when_service_not_found(): void
    {
        $container = new Container();

        $this->expectException(ServiceNotFoundException::class);

        $container->get('unknown');
    }

    public function test_bind_can_reference_another_registered_service_id(): void
    {
        $container = new Container();

        $service = new \stdClass();

        $container->instance('service', $service);
        $container->bind('service.alias', 'service');

        $this->assertTrue(
            $container->has('service.alias')
        );

        $this->assertSame(
            $service,
            $container->get('service.alias')
        );
    }
}
