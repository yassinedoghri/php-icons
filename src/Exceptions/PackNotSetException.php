<?php

declare(strict_types=1);

namespace PHPIconify\Exceptions;

use RuntimeException;

class PackNotSetException extends RuntimeException
{
    public static function forEmptyIconPack(string $icon): self
    {
        return new self(sprintf('Icon pack is missing for icon "%s". Forgot to include it?', $icon));
    }
}
