<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Velt\Kernel\Application;
use Velt\Kernel\Contracts\ExceptionHandlerInterface;
use Velt\Kernel\Tests\Fixtures\FakeExceptionHandler;

final class ApplicationExceptionHandlerTest extends TestCase
{
    public function test_application_exposes_exception_handler(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            ExceptionHandlerInterface::class,
            $app->exceptions()
        );
    }

    public function test_exception_handler_is_registered_in_container(): void
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(
            ExceptionHandlerInterface::class,
            $app->container()->get('exceptions')
        );
    }

    public function test_debug_mode_is_propagated_to_exception_handler(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-debug-handler';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            "APP_DEBUG=true"
        );

        $app = new Application($basePath);

        $result = $app
            ->exceptions()
            ->render(
                new RuntimeException('Debug enabled')
            );

        $this->assertSame(
            'Debug enabled',
            $result['message']
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_production_mode_hides_exception_details(): void
    {
        $basePath = sys_get_temp_dir() . '/velt-production-handler';

        if (! is_dir($basePath)) {
            mkdir($basePath);
        }

        file_put_contents(
            $basePath . '/.env',
            "APP_DEBUG=false"
        );

        $app = new Application($basePath);

        $result = $app
            ->exceptions()
            ->render(
                new RuntimeException('Sensitive error')
            );

        $this->assertSame(
            'An internal error occurred.',
            $result['message']
        );

        $this->assertArrayNotHasKey(
            'trace',
            $result
        );

        unlink($basePath . '/.env');

        rmdir($basePath);
    }

    public function test_fake_exception_handler_reports_exception(): void
    {
        $handler = new FakeExceptionHandler();

        $exception = new RuntimeException(
            'Test exception'
        );

        $handler->report(
            $exception
        );

        $this->assertTrue(
            $handler->reported
        );

        $this->assertSame(
            $exception,
            $handler->exception
        );
    }

    public function test_fake_exception_handler_renders_exception_message(): void
    {
        $handler = new FakeExceptionHandler();

        $exception = new RuntimeException(
            'Render test'
        );

        $result = $handler->render(
            $exception
        );

        $this->assertFalse(
            $result['success']
        );

        $this->assertSame(
            'Render test',
            $result['message']
        );
    }
}