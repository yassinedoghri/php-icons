<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([__DIR__ . DIRECTORY_SEPARATOR . 'src', __DIR__ . DIRECTORY_SEPARATOR . 'tests'])
    ->withPhpSets(php81: true)
    ->withPhpVersion(PhpVersion::PHP_84)
    ->withPreparedSets(deadCode: true, codeQuality: true)
    ->withSkip([
        RemoveUselessVarTagRector::class   => [__DIR__ . '/src/PHPIcons.php'],
        StringableForToStringRector::class => [__DIR__ . '/src/PHPIcons.php'],
    ])
    ->withRules([ExplicitNullableParamTypeRector::class, VariableInStringInterpolationFixerRector::class]);
