<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\ConfigTests;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Config\ConfigRepository;

final class ConfigRepositoryTest extends TestCase
{
    public function test_can_get_simple_value(): void
    {
        $config = new ConfigRepository([
            'app' => [
                'name' => 'Velt',
            ],
        ]);

        $this->assertSame(
            'Velt',
            $config->get('app.name')
        );
    }

    public function test_returns_default_value_when_key_does_not_exist(): void
    {
        $config = new ConfigRepository();

        $this->assertSame(
            'default',
            $config->get('app.name', 'default')
        );
    }

    public function test_can_set_value_using_dot_notation(): void
    {
        $config = new ConfigRepository();

        $config->set('database.default', 'mysql');

        $this->assertSame(
            'mysql',
            $config->get('database.default')
        );
    }

    public function test_has_returns_true_when_key_exists(): void
    {
        $config = new ConfigRepository([
            'app' => [
                'env' => 'local',
            ],
        ]);

        $this->assertTrue(
            $config->has('app.env')
        );
    }

    public function test_has_returns_false_when_key_does_not_exist(): void
    {
        $config = new ConfigRepository();

        $this->assertFalse(
            $config->has('missing.key')
        );
    }

    public function test_all_returns_all_configuration(): void
    {
        $items = [
            'app' => [
                'name' => 'Velt',
            ],
        ];

        $config = new ConfigRepository($items);

        $this->assertSame(
            $items,
            $config->all()
        );
    }
}
