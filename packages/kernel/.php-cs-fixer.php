<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return (new Config())
    ->setRiskyAllowed(false)
    ->setUsingCache(true)
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);
