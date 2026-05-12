<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un objet pouvant être converti en JSON.
 */
interface JsonableInterface
{
    /**
     * Convertit l'objet en chaîne JSON.
     */
    public function toJson(): string;
}