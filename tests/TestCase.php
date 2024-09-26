<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Main test case
 *
 * @see https://github.com/nunomaduro/laravel.io/blob/main/tests/TestCase.php
 */
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // cleanup: delete Icons.php to start fresh ✨
        // add @ to discard "No such file or directory" warning if file does not exist
        @unlink(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Icons.php');
    }
}
