<?php

declare(strict_types=1);

use Tests\TestCase;
use Tests\TestHelperTrait;

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Constants.php');

/**
 * @see https://pestphp.com/docs/configuring-tests
 */
uses(TestCase::class)
    ->group('feature')
    ->use(TestHelperTrait::class)
    ->in('Feature');
