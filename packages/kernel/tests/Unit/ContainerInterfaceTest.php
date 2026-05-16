<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Contracts;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\ContainerInterface;
use Velt\Kernel\Tests\Fixtures\FakeContainer;

final class ContainerInterfaceTest extends TestCase
{
    public function test_fake_container_implements_contract(): void
    {
        $container = new FakeContainer();

        $this->assertInstanceOf(
            ContainerInterface::class,
            $container
        );
    }

    public function test_fake_container_can_store_and_resolve_service(): void
    {
        $container = new FakeContainer();

        $container->bind('service', 'test');

        $this->assertSame(
            'test',
            $container->get('service')
        );
    }
}