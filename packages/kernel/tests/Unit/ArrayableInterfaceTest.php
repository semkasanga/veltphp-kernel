<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\ArrayableInterface;
use Velt\Kernel\Tests\Fixtures\FakeArrayable;

final class ArrayableInterfaceTest extends TestCase
{
    public function test_fake_implements_arrayable_contract(): void
    {
        $fake = new FakeArrayable();

        $this->assertInstanceOf(
            ArrayableInterface::class,
            $fake
        );
    }

    public function test_fake_can_be_converted_to_array(): void
    {
        $fake = new FakeArrayable();

        $this->assertSame(
            [
                'name' => 'Velt',
                'type' => 'framework',
            ],
            $fake->toArray()
        );
    }
}