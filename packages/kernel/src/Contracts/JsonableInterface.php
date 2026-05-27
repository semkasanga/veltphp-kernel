<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un objet pouvant être converti en JSON.
 */
interface JsonableInterface
{
    /**
     * Convertit l'objet en JSON.
     *
     * Peut retourner :
     * - une chaîne JSON déjà encodée ;
     * - un tableau PHP à encoder.
     *
     * @return string|array<mixed>
     */
    public function toJson(): string|array;
}