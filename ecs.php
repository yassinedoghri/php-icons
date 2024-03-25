<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->sets([
        SetList::CLEAN_CODE,
        SetList::COMMON,
        SetList::SYMPLIFY,
        SetList::PSR_12,
        SetList::CONTROL_STRUCTURES,
    ]);

    $ecsConfig->ruleWithConfiguration(BinaryOperatorSpacesFixer::class, [
        'operators' => [
            '=>' => 'align_single_space_minimal',
        ],
    ]);
};
