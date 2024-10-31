<?php

declare(strict_types=1);

use PHPIcons\PHPIcons;

/**
 * Class to spawn PHPIcons object once and reuse it in icon() helper function
 */
class PHPIconsSingleton
{
    private static ?PHPIcons $instance = null;

    public static function getInstance(): PHPIcons
    {
        if (! self::$instance instanceof \PHPIcons\PHPIcons) {
            self::$instance = new PHPIcons();
        }

        return self::$instance;
    }
}

/**
 * Provides convenient access to rendering an icon with optional attributes using the PHPIcons class
 *
 * @param array<string, string> $attributes
 */
function icon(string $iconKey, array $attributes = []): PHPIcons
{
    $phpIcons = PHPIconsSingleton::getInstance();

    return $phpIcons
        ->icon($iconKey)
        ->attributes($attributes);
}
