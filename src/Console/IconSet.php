<?php

declare(strict_types=1);

namespace PHPIcons\Console;

class IconSet
{
    /**
     * @var array<string,Icon>
     */
    private array $icons = [];

    private bool $found = false;

    public function __construct(
        private readonly string $prefix
    ) {
    }

    public function addIcon(Icon $icon): void
    {
        if (in_array($icon->getName(), $this->getIconNames(), true)) {
            $this->icons[$icon->getName()]->merge($icon);
        } else {
            $this->icons[$icon->getName()] = $icon;
        }
    }

    public function setFound(): void
    {
        $this->found = true;
    }

    public function setIconFound(string $iconName, string $svg): void
    {
        $this->icons[$iconName]->setFound();
        $this->icons[$iconName]->setSVG($svg);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return array<string,Icon>
     */
    public function getIcons(): array
    {
        return $this->icons;
    }

    public function isFound(): bool
    {
        return $this->found;
    }

    /**
     * @return string[]
     */
    public function getIconNames(): array
    {
        return array_keys($this->icons);
    }
}
