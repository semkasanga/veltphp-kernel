<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\JsonableInterface;
use Velt\Kernel\Tests\Fixtures\FakeJsonable;

final class JsonableInterfaceTest extends TestCase
{
    public function test_fake_implements_jsonable_contract(): void
    {
        $fake = new FakeJsonable();

        $this->assertInstanceOf(
            JsonableInterface::class,
            $fake
        );
    }

    public function test_fake_can_be_converted_to_json(): void
    {
        $fake = new FakeJsonable();

        $json = $fake->toJson();

        $this->assertIsString(
            $json
        );

        $this->assertJson(
            $json
        );
    }

    public function test_fake_returns_expected_json_structure(): void
    {
        $fake = new FakeJsonable();

        $this->assertSame(
            json_encode([
                'name' => 'Velt',
                'type' => 'framework',
            ]),
            $fake->toJson()
        );
    }
}