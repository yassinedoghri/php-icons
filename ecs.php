<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . DIRECTORY_SEPARATOR . 'src', __DIR__ . DIRECTORY_SEPARATOR . 'tests'])
    ->withRootFiles()
    ->withPreparedSets(cleanCode: true, common: true, symplify: true, psr12: true, strict: true)
    ->withSkip([__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Icons.php'])
    ->withConfiguredRule(BinaryOperatorSpacesFixer::class, [
        'operators' => [
            '=>' => 'align_single_space_minimal',
        ],
    ]);
