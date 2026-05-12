<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Tests\Fixtures\FakeContainer;

final class ContainerInterfaceTest extends TestCase
{
    public function test_fake_implements_container_contract(): void
    {
        $container = new FakeContainer();

        $this->assertInstanceOf(
            ContainerInterface::class,
            $container
        );
    }

    public function test_it_can_bind_and_resolve_service(): void
    {
        $container = new FakeContainer();

        $service = new \stdClass();

        $container->bind('service', $service);

        $this->assertSame(
            $service,
            $container->make('service')
        );
    }

    public function test_it_can_register_singleton(): void
    {
        $container = new FakeContainer();

        $service = new \stdClass();

        $container->singleton('singleton', $service);

        $this->assertSame(
            $service,
            $container->make('singleton')
        );
    }

    public function test_it_can_check_if_binding_exists(): void
    {
        $container = new FakeContainer();

        $container->bind('service', new \stdClass());

        $this->assertTrue(
            $container->has('service')
        );
    }

    public function test_it_throws_exception_when_service_does_not_exist(): void
    {
        $container = new FakeContainer();

        $this->expectException(RuntimeException::class);

        $container->make('unknown');
    }
}