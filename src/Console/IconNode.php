<?php

declare(strict_types=1);

namespace PHPIcons\Console;

use PhpParser\Node\Scalar\String_;
use RuntimeException;

class IconNode
{
    public function __construct(
        private readonly string $filePath,
        private readonly String_ $node
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getLineNumber(): int
    {
        return $this->node->getLine();
    }

    public function getColumnNumber(): int
    {
        $code = file_get_contents($this->filePath);

        if (! $code) {
            throw new RuntimeException(sprintf('Something happened when getting %s', $this->filePath));
        }

        $filePosition = $this->node->getStartFilePos();

        if ($filePosition > strlen($code)) {
            throw new \RuntimeException('Invalid position information');
        }

        $lineStartPos = strrpos($code, "\n", $filePosition - strlen($code));
        if ($lineStartPos === false) {
            $lineStartPos = -1;
        }

        return $filePosition - $lineStartPos;
    }
}
