<?php

declare(strict_types=1);

namespace PHPIconify\Exceptions;

use RuntimeException;

class PHPIconifyMisuseException extends RuntimeException
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

    public static function forWrongIconFormat(): self
    {
        return new self(
            'Wrong Iconify icon format. Format must contain both the pack and icon name separated by a colon: "{pack}:{icon}".'
        );
    }

    public static function forEmptyIcon(): self
    {
        return new self('Icon name is empty. Forgot to include it?');
    }
}
