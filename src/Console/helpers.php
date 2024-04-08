<?php

declare(strict_types=1);

if (! function_exists('glob_recursive')) {
    /**
     * Adapted from https://www.php.net/manual/en/function.glob.php#106595
     *
     * @return string[]|false
     */
    function glob_recursive(string $pattern, int $flags = 0): array|false
    {
        $files = glob($pattern, $flags);

        if (! $files) {
            return false;
        }

        $directories = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        if (! $directories) {
            $directories = [];
        }

        foreach ($directories as $directory) {
            $filesFound = glob_recursive($directory . '/' . basename($pattern), $flags);

            if (! $filesFound) {
                continue;
            }

            $files = array_merge($files, $filesFound);
        }

        return $files;
    }
}

if (! function_exists('varexport')) {
    /**
     * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
     *
     * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
     * @link https://www.php.net/manual/en/function.var-export.php
     */
    function varexport(mixed $expression): string
    {
        $export = var_export($expression, true);
        $patterns = [
            "/array \(/"                       => '[',
            "/^([ ]*)\)(,?)$/m"                => '$1]$2',
            "/=>[ ]?\n[ ]+\[/"                 => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];

        return (string) preg_replace(array_keys($patterns), array_values($patterns), $export);
    }
}
