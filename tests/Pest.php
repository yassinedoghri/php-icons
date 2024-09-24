<?php

declare(strict_types=1);

use Tests\TestCase;

/**
 * @see https://pestphp.com/docs/configuring-tests
 */
uses(TestCase::class)
    ->group('unit')
    ->in('Unit');
    
uses(TestCase::class)
    ->group('feature')
    ->in('Feature');