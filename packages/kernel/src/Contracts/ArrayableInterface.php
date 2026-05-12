<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un objet pouvant être converti en tableau.
 */
interface ArrayableInterface
{
    /**
     * Convertit l'objet en tableau.
     *
     * @return array<mixed>
     */
    public function toArray(): array;
}