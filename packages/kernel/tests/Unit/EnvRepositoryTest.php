<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Env\EnvRepository;

final class EnvRepositoryTest extends TestCase
{
    public function test_it_sets_and_gets_variables(): void
    {
        $env = new EnvRepository();

        $env->set('APP_NAME', 'Velt');

        $this->assertSame(
            'Velt',
            $env->get('APP_NAME')
        );
    }

    public function test_it_returns_default_value(): void
    {
        $env = new EnvRepository();

        $this->assertSame(
            'local',
            $env->get('APP_ENV', 'local')
        );
    }

    public function test_it_checks_if_variable_exists(): void
    {
        $env = new EnvRepository();

        $env->set('APP_ENV', 'testing');

        $this->assertTrue(
            $env->has('APP_ENV')
        );

        $this->assertFalse(
            $env->has('UNKNOWN_KEY')
        );
    }

    public function test_it_loads_env_file(): void
    {
        $path = sys_get_temp_dir() . '/velt-test.env';

        file_put_contents(
            $path,
            "APP_ENV=testing\nAPP_DEBUG=true"
        );

        $env = new EnvRepository();

        $env->load($path);

        $this->assertSame(
            'testing',
            $env->get('APP_ENV')
        );

        $this->assertTrue(
            $env->get('APP_DEBUG')
        );

        unlink($path);
    }

    public function test_it_casts_false_boolean(): void
    {
        $path = sys_get_temp_dir() . '/velt-false.env';

        file_put_contents(
            $path,
            "APP_DEBUG=false"
        );

        $env = new EnvRepository();

        $env->load($path);

        $this->assertFalse(
            $env->get('APP_DEBUG')
        );

        unlink($path);
    }

    public function test_it_ignores_comments_and_empty_lines(): void
    {
        $path = sys_get_temp_dir() . '/velt-comments.env';

        file_put_contents(
            $path,
            "# Comment\n\nAPP_ENV=local"
        );

        $env = new EnvRepository();

        $env->load($path);

        $this->assertSame(
            'local',
            $env->get('APP_ENV')
        );

        unlink($path);
    }
}