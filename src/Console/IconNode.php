<?php

declare(strict_types=1);

namespace PHPIcons\Console;

use RuntimeException;

class IconNode
{
    public function __construct(
        private readonly string $filePath,
        private readonly int $lineNumber,
        private readonly int $startFilePos,
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function getColumnNumber(): int
    {
        $code = file_get_contents($this->filePath);

        if (! $code) {
            throw new RuntimeException(sprintf('Something happened when getting %s', $this->filePath));
        }

        $filePosition = $this->startFilePos;

        if ($filePosition > strlen($code)) {
            throw new \RuntimeException('Invalid position information');
        }

        $lineStartPos = strrpos($code, PHP_EOL, $filePosition - strlen($code));
        if ($lineStartPos === false) {
            $lineStartPos = -1;
        }

        return $filePosition - $lineStartPos;
    }
}
