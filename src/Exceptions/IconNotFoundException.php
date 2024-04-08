<?php

declare(strict_types=1);

namespace PHPIcons\Exceptions;

use RuntimeException;

class IconNotFoundException extends RuntimeException
{
    public static function forKeyNotFound(string $iconKey): self
    {
        return new self(sprintf('Icon "%s" not found. Forgot to run `vendor/bin/php-icons scan` command?', $iconKey));
    }
}
