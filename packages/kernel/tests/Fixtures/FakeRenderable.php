<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Velt\Kernel\Contracts\RenderableInterface;

final class FakeRenderable implements RenderableInterface
{
    public function render(): string
    {
        return '<h1>Velt Framework</h1>';
    }
}