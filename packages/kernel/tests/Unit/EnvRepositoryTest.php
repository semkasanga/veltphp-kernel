<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
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

    public function test_it_parses_quoted_values_and_trims_surrounding_spaces(): void
    {
        $path = sys_get_temp_dir() . '/velt-quoted.env';

        file_put_contents(
            $path,
            "APP_NAME = \"Velt Kernel\"\nAPP_DEBUG = 'false'"
        );

        $env = new EnvRepository();

        $env->load($path);

        $this->assertSame(
            'Velt Kernel',
            $env->get('APP_NAME')
        );

        $this->assertFalse(
            $env->get('APP_DEBUG')
        );

        unlink($path);
    }

    public function test_it_lets_duplicate_variables_override_previous_values(): void
    {
        $path = sys_get_temp_dir() . '/velt-duplicate.env';

        file_put_contents(
            $path,
            "APP_ENV=local\nAPP_ENV=testing"
        );

        $env = new EnvRepository();

        $env->load($path);

        $this->assertSame(
            'testing',
            $env->get('APP_ENV')
        );

        unlink($path);
    }

    public function test_it_throws_when_the_env_file_is_missing(): void
    {
        $env = new EnvRepository();

        $this->expectException(
            RuntimeException::class
        );

        $env->load(
            sys_get_temp_dir() . '/missing-velt-env-file.env'
        );
    }

    public function test_it_throws_when_a_line_is_malformed(): void
    {
        $path = sys_get_temp_dir() . '/velt-malformed.env';

        file_put_contents(
            $path,
            "APP_ENV=testing\nBROKEN_LINE"
        );

        $env = new EnvRepository();

        $this->expectException(
            RuntimeException::class
        );

        try {
            $env->load($path);
        } finally {
            unlink($path);
        }
    }

    public function test_it_throws_when_a_key_is_invalid(): void
    {
        $path = sys_get_temp_dir() . '/velt-invalid-key.env';

        file_put_contents(
            $path,
            "APP-ENV=testing"
        );

        $env = new EnvRepository();

        $this->expectException(
            RuntimeException::class
        );

        try {
            $env->load($path);
        } finally {
            unlink($path);
        }
    }

    public function test_it_keeps_existing_values_when_loading_invalid_file(): void
    {
        $path = sys_get_temp_dir() . '/velt-invalid-atomic.env';

        file_put_contents(
            $path,
            "APP_ENV=testing\nBROKEN_LINE"
        );

        $env = new EnvRepository();
        $env->set(
            'APP_ENV',
            'local'
        );

        try {
            $env->load($path);

            $this->fail(
                'Loading an invalid environment file should fail.'
            );
        } catch (RuntimeException) {
            $this->assertSame(
                'local',
                $env->get('APP_ENV')
            );
        } finally {
            unlink($path);
        }
    }
}
