<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Velt\Kernel\Contracts\ConnectionInterface;
use Velt\Kernel\Contracts\DatabaseManagerInterface;
use Velt\Kernel\Contracts\DriverInterface;

final class DatabaseContractTest extends TestCase
{
    public function test_connection_interface_is_coherent(): void
    {
        $this->assertMethodReturnType(
            ConnectionInterface::class,
            'name',
            'string'
        );

        $this->assertMethodReturnType(
            ConnectionInterface::class,
            'driver',
            DriverInterface::class
        );

        $this->assertMethodReturnType(
            ConnectionInterface::class,
            'connect',
            'void'
        );

        $this->assertMethodReturnType(
            ConnectionInterface::class,
            'disconnect',
            'void'
        );

        $this->assertMethodReturnType(
            ConnectionInterface::class,
            'isConnected',
            'bool'
        );
    }

    public function test_driver_interface_creates_connections(): void
    {
        $this->assertMethodReturnType(
            DriverInterface::class,
            'name',
            'string'
        );

        $this->assertMethodReturnType(
            DriverInterface::class,
            'connection',
            ConnectionInterface::class
        );
    }

    public function test_database_manager_interface_coordinates_drivers_and_connections(): void
    {
        $this->assertMethodReturnType(
            DatabaseManagerInterface::class,
            'connection',
            ConnectionInterface::class
        );

        $this->assertMethodReturnType(
            DatabaseManagerInterface::class,
            'driver',
            DriverInterface::class
        );

        $this->assertMethodReturnType(
            DatabaseManagerInterface::class,
            'hasConnection',
            'bool'
        );

        $this->assertMethodReturnType(
            DatabaseManagerInterface::class,
            'hasDriver',
            'bool'
        );
    }

    private function assertMethodReturnType(
        string $class,
        string $method,
        string $expected
    ): void {
        $returnType = (new ReflectionMethod(
            $class,
            $method
        ))->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertSame(
            $expected,
            $returnType->getName()
        );
    }
}
