<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\ConfigTests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Velt\Kernel\Application;
use Velt\Kernel\Contracts\ConfigRepositoryInterface;

final class ConfigRepositoryIntegrationTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        $this->basePath = sys_get_temp_dir() . '/velt-kernel-config-' . uniqid('', true);

        mkdir($this->basePath . '/config', 0777, true);

        file_put_contents(
            $this->basePath . '/.env',
            "APP_ENV=testing\nAPP_DEBUG=false"
        );

        /**
         * config/app.php
         */
        file_put_contents(
            $this->basePath . '/config/app.php',
            '<?php return [
                "name" => "VeltTest",
                "debug" => true,
            ];'
        );
    }

    protected function tearDown(): void
    {
        $this->deleteDir($this->basePath);
    }

    public function test_config_repository_is_resolved_and_config_files_are_loaded(): void
    {
        $app = new Application($this->basePath);

        $config = $app->container()->get(ConfigRepositoryInterface::class);

        $this->assertInstanceOf(ConfigRepositoryInterface::class, $config);

        /**
         * Test config chargée depuis config/app.php
         */
        $this->assertSame('VeltTest', $config->get('app.name'));
        $this->assertTrue($config->get('app.debug'));
    }

    public function test_container_alias_config_returns_same_instance(): void
    {
        $app = new Application($this->basePath);

        $this->assertSame(
            $app->container()->get('config'),
            $app->container()->get(ConfigRepositoryInterface::class)
        );
    }

    public function test_runtime_configuration_overrides_file_configuration_without_losing_other_values(): void
    {
        $app = new Application(
            $this->basePath,
            [
                'app' => [
                    'name' => 'RuntimeVelt',
                ],
            ]
        );

        $config = $app->config();

        $this->assertSame(
            'RuntimeVelt',
            $config->get('app.name')
        );

        $this->assertTrue(
            $config->get('app.debug')
        );
    }

    public function test_invalid_configuration_file_throws_exception(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-kernel-invalid-config-' . uniqid('', true);

        mkdir($basePath . '/config', 0777, true);

        file_put_contents(
            $basePath . '/.env',
            'APP_ENV=production'
        );

        file_put_contents(
            $basePath . '/config/app.php',
            '<?php return "invalid";'
        );

        $this->expectException(
            RuntimeException::class
        );

        try {
            new Application($basePath);
        } finally {
            $this->deleteDir($basePath);
        }
    }

    private function deleteDir(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (scandir($path) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $full = $path . '/' . $file;

            if (is_dir($full)) {
                $this->deleteDir($full);
            } else {
                unlink($full);
            }
        }

        rmdir($path);
    }
}
