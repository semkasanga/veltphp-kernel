<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Fixtures;

use Throwable;
use Velt\Kernel\Contracts\ExceptionHandlerInterface;

final class FakeExceptionHandler implements ExceptionHandlerInterface
{
    public bool $reported = false;

    public ?Throwable $exception = null;

    public function report(Throwable $exception): void
    {
        $this->reported = true;

        $this->exception = $exception;
    }

    public function render(
        Throwable $exception,
        mixed $context = null
    ): array {
        return [
            'success' => false,
            'message' => $exception->getMessage(),
        ];
    }
}
