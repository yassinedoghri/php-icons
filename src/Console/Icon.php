<?php

declare(strict_types=1);

namespace PHPIcons\Console;

use PhpParser\Node\Scalar\String_;

class Icon
{
    private string $prefix = '';

    private string $name = '';

    private string $svg = '';

    private bool $found = false;

    /**
     * @var IconNode[]
     */
    private array $nodes = [];

    public function __construct(string $filePath, String_ $node, string $defaultPrefix = '')
    {
        $this->nodes[] = new IconNode($filePath, $node);

        $iconKey = $node->value;

        if (str_contains($iconKey, ':')) {
            $icon = explode(':', $iconKey);
            $this->prefix = $icon[0];
            $this->name = $icon[1];
        } else {
            $this->name = $iconKey;
        }

        if ($this->prefix === '') {
            $this->prefix = $defaultPrefix;
        }
    }

    public function merge(self $icon): void
    {
        $this->nodes = [...$this->nodes, ...$icon->getNodes()];
    }

    public function setSVG(string $svg): void
    {
        $this->svg = $svg;
    }

    public function setFound(): void
    {
        $this->found = true;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return IconNode[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getSVG(): string
    {
        return $this->svg;
    }

    public function isFound(): bool
    {
        return $this->found;
    }

    public function isEmpty(): bool
    {
        return $this->svg === '';
    }

    public function getKey(): string
    {
        return "{$this->prefix}:{$this->name}";
    }
}
