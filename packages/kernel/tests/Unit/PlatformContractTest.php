<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Velt\Kernel\Contracts\DesktopPlatformInterface;
use Velt\Kernel\Contracts\MobilePlatformInterface;
use Velt\Kernel\Contracts\PlatformInterface;
use Velt\Kernel\Contracts\RuntimeInterface;

final class PlatformContractTest extends TestCase
{
    public function test_platform_interface_is_runtime_aware(): void
    {
        $this->assertTrue(
            is_subclass_of(
                PlatformInterface::class,
                RuntimeInterface::class
            )
        );
    }

    public function test_platform_interface_exposes_name_and_capabilities(): void
    {
        $this->assertMethodReturnType(
            PlatformInterface::class,
            'name',
            'string'
        );

        $this->assertMethodReturnType(
            PlatformInterface::class,
            'capabilities',
            'array'
        );
    }

    public function test_desktop_and_mobile_platforms_extend_platform_interface(): void
    {
        $this->assertTrue(
            is_subclass_of(
                DesktopPlatformInterface::class,
                PlatformInterface::class
            )
        );

        $this->assertTrue(
            is_subclass_of(
                MobilePlatformInterface::class,
                PlatformInterface::class
            )
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
