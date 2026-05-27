<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Velt\Kernel\Contracts\JsonableInterface;

final class FakeJsonable implements JsonableInterface
{
    public function toJson(): string|array
    {
        return json_encode([
            'name' => 'Velt',
            'type' => 'framework',
        ]);
    }
}