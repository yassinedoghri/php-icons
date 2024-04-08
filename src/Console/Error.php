<?php

declare(strict_types=1);

namespace PHPIcons\Console;

class Error
{
    private readonly string $title;

    /**
     * @var string[]
     */
    private readonly array $paths;

    public function __construct(IconSet|Icon $element)
    {
        if ($element instanceof IconSet) {
            $title = sprintf('ICON SET "%s" NOT FOUND', $element->getPrefix());
            $paths = [];
            foreach ($element->getIcons() as $icon) {
                foreach ($icon->getNodes() as $node) {
                    $paths[] = sprintf(
                        '%s:%s:%s',
                        $node->getFilePath(),
                        (string) $node->getLineNumber(),
                        (string) $node->getColumnNumber()
                    );
                }
            }
        } else {
            $title = sprintf('ICON "%s" NOT FOUND', $element->getKey());
            $paths = [];
            foreach ($element->getNodes() as $node) {
                $paths[] = sprintf(
                    '%s:%s:%s',
                    $node->getFilePath(),
                    (string) $node->getLineNumber(),
                    (string) $node->getColumnNumber()
                );
            }
        }

        $this->title = $title;
        $this->paths = $paths;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function getLocations(): array
    {
        return $this->paths;
    }
}
