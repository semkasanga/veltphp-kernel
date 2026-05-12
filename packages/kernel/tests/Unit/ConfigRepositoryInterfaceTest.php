<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;
use Velt\Kernel\Tests\Fixtures\FakeConfigRepository;

final class ConfigRepositoryInterfaceTest extends TestCase
{
    public function test_fake_implements_config_repository_contract(): void
    {
        $config = new FakeConfigRepository();

        $this->assertInstanceOf(
            ConfigRepositoryInterface::class,
            $config
        );
    }

    public function test_it_can_get_configuration_value(): void
    {
        $config = new FakeConfigRepository();

        $this->assertSame(
            'Velt',
            $config->get('app.name')
        );
    }

    public function test_it_returns_default_value_when_key_does_not_exist(): void
    {
        $config = new FakeConfigRepository();

        $this->assertSame(
            'default',
            $config->get('unknown.key', 'default')
        );
    }

    public function test_it_can_set_configuration_value(): void
    {
        $config = new FakeConfigRepository();

        $config->set('app.env', 'local');

        $this->assertSame(
            'local',
            $config->get('app.env')
        );
    }

    public function test_it_can_check_if_key_exists(): void
    {
        $config = new FakeConfigRepository();

        $this->assertTrue(
            $config->has('app.name')
        );
    }

    public function test_it_can_return_all_configuration(): void
    {
        $config = new FakeConfigRepository();

        $this->assertIsArray(
            $config->all()
        );
    }
}