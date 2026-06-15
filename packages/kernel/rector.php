<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withSkip([
        ReadOnlyClassRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        StringClassNameToClassConstantRector::class,
        ArrowFunctionDelegatingCallToFirstClassCallableRector::class,
        ReadOnlyPropertyRector::class,
        ClassOnObjectRector::class,
    ]);
