<?php

declare(strict_types=1);

namespace Velt\Kernel;

use Velt\Kernel\Contracts\ApplicationInterface;
use Velt\Kernel\Contracts\ServiceProviderInterface;

abstract class ServiceProvider implements ServiceProviderInterface
{
    protected ApplicationInterface $app;

    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
    }
}