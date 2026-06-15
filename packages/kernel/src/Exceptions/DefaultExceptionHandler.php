<?php

declare(strict_types=1);

namespace Velt\Kernel\Exceptions;

use Throwable;
use Velt\Kernel\Contracts\ExceptionHandlerInterface;

final class DefaultExceptionHandler
implements ExceptionHandlerInterface
{
    public function __construct(
        private readonly bool $debug = false
    ) {}

    public function report(
        Throwable $exception
    ): void {
        error_log(
            sprintf(
                '[%s] %s in %s:%d',
                $exception::class,
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function render(
        Throwable $exception,
        mixed $context = null
    ): array {
        if ($this->debug) {
            return [
                'success' => false,
                'type' => $exception::class,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        }

        return [
            'success' => false,
            'message' => 'An internal error occurred.',
        ];
    }

    public function handle(
        Throwable $exception,
        mixed $context = null
    ): array {
        $this->report($exception);

        return $this->render(
            $exception,
            $context
        );
    }
}
