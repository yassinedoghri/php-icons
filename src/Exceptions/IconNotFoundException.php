<?php

declare(strict_types=1);

namespace PHPIconify\Exceptions;

use RuntimeException;

class IconNotFoundException extends RuntimeException
{
    public static function forFileNotFound(string $iconPath): self
    {
        return new self(sprintf('Could not locate icon file in "%s"', $iconPath));
    }

    public static function forHostReturned404(string $host, string $pack, string $icon): self
    {
        return new self(
            sprintf('Could not find svg icon "%s:%s". Iconify host "%s" returned a 404.', $pack, $icon, $host)
        );
    }
}
