<?php

declare(strict_types=1);

namespace PHPIconify\Exceptions;

use RuntimeException;

class LibraryMisuseException extends RuntimeException
{
    public static function forMissingAPIHosts(): self
    {
        return new self(
            'Make sure to include at least one iconify API host in the options when instantiating the library.'
        );
    }

    public static function forUndefinedIcon(): self
    {
        return new self('You must define an icon before rendering it. Use the `icon()` method to do so.');
    }

    public static function forMissingIconPack(): self
    {
        return new self(
            'Icon pack is missing! You may set a default_pack in your options or specify it as a prefix: {pack}:{icon}.'
        );
    }

    public static function forEmptyIcon(): self
    {
        return new self('Icon name is empty. Forgot to include it?');
    }

    public static function forEmptyIconPack(string $icon): self
    {
        return new self(sprintf('Icon pack is missing for icon "%s". Forgot to include it?', $icon));
    }
}
