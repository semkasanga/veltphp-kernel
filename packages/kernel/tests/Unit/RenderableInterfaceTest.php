<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\RenderableInterface;
use Velt\Kernel\Tests\Fixtures\FakeRenderable;

final class RenderableInterfaceTest extends TestCase
{
    public function test_fake_implements_renderable_contract(): void
    {
        $fake = new FakeRenderable();

        $this->assertInstanceOf(
            RenderableInterface::class,
            $fake
        );
    }

    public function test_fake_can_render_content(): void
    {
        $fake = new FakeRenderable();

        $this->assertSame(
            '<h1>Velt Framework</h1>',
            $fake->render()
        );
    }
}