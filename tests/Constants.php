<?php

declare(strict_types=1);

if (! defined('TEST_SRC_PATH')) {
    define(
        'TEST_SRC_PATH',
        __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
    );
}

if (! defined('TEST_ICONS_FILE_PATH')) {
    define('TEST_ICONS_FILE_PATH', TEST_SRC_PATH . 'Icons.php');
}

if (! defined('TEST_FIXTURES_PATH')) {
    define('TEST_FIXTURES_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR);
}

if (! defined('TEST_SNAPSHOTS_PATH')) {
    define('TEST_SNAPSHOTS_PATH', TEST_FIXTURES_PATH . 'snapshots' . DIRECTORY_SEPARATOR);
}

if (! defined('TEST_VIEWS_PATH')) {
    define('TEST_VIEWS_PATH', TEST_FIXTURES_PATH . 'views' . DIRECTORY_SEPARATOR);
}

if (! defined('TEST_TEMP_VIEWS_PATH')) {
    define('TEST_TEMP_VIEWS_PATH', TEST_FIXTURES_PATH . 'temp_views' . DIRECTORY_SEPARATOR);
}

define('CLIENT_ROOTPATH', TEST_FIXTURES_PATH);
