<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Représente un objet pouvant être rendu.
 */
interface RenderableInterface
{
    /**
     * Retourne une représentation rendue de l'objet.
     */
    public function render(): string;
}