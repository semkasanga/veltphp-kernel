<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

use Throwable;

/**
 * Représente un gestionnaire centralisé d'exceptions.
 */
interface ExceptionHandlerInterface
{
    /**
     * Reporte une exception.
     */
    public function report(Throwable $exception): void;

    /**
     * Transforme une exception en structure exploitable.
     *
     * @return array<string, mixed>
     */
    public function render(
        Throwable $exception,
        mixed $context = null
    ): array;

    /**
     * Gère complètement une exception.
     *
     * Effectue le report puis retourne
     * la représentation exploitable.
     *
     * @return array<string,mixed>
     */
    public function handle(
        Throwable $exception,
        mixed $context = null
    ): array;
}
