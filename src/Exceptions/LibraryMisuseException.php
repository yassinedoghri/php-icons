<?php

declare(strict_types=1);

namespace PHPIcons\Exceptions;

use RuntimeException;

class LibraryMisuseException extends RuntimeException
{
    public static function forUndefinedIcon(): self
    {
        return new self('You must define an icon before rendering it. Use the `icon()` method to do so.');
    }

    public static function forMissingPrefix(): self
    {
        return new self(
            'Icon set prefix is missing! You may set a default prefix in your config or add it: {prefix}:{icon}.'
        );
    }

    public static function forEmptyIcon(): self
    {
        return new self('Icon name is empty. Forgot to include it?');
    }

    public static function forEmptyPrefix(string $icon): self
    {
        return new self(sprintf('Icon set prefix is missing for icon "%s". Forgot to include it?', $icon));
    }
}
