<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;

final class ApplicationTest extends TestCase
{
    public function test_it_exposes_version(): void
    {
        $app = new Application();

        $this->assertSame('0.1.0', $app::VERSION);
    }
}