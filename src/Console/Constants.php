<?php

declare(strict_types=1);

if (! defined('COMPOSER_AUTOLOAD_PATH')) {
    define('COMPOSER_AUTOLOAD_PATH', $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php');
}

if (! defined('CLIENT_ROOTPATH')) {
    define('CLIENT_ROOTPATH', dirname((string) COMPOSER_AUTOLOAD_PATH, 4) . DIRECTORY_SEPARATOR);
}
