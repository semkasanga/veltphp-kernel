<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Velt\Kernel\ServiceProvider;

final class FakeServiceProvider extends ServiceProvider
{
    /**
     * @var array<int, string>
     */
    public static array $events = [];

    public function register(): void
    {
        self::$events[] = 'register';

        $this->app
            ->container()
            ->instance(
                'fake.service',
                new \stdClass()
            );
    }

    public function boot(): void
    {
        self::$events[] = 'boot';
    }
}