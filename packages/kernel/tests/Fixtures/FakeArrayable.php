<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Velt\Kernel\Contracts\ArrayableInterface;

final class FakeArrayable implements ArrayableInterface
{
    public function toArray(): array
    {
        return [
            'name' => 'Velt',
            'type' => 'framework',
        ];
    }
}