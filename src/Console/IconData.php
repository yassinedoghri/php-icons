<?php

declare(strict_types=1);

namespace PHPIcons\Console;

class IconData
{
    /**
     * @var array<string,IconSet>
     */
    private array $iconSets = [];

    /**
     * @var array<string,string>
     */
    private array $new = [];

    /**
     * @var array{iconSet:Error[],icon:Error[]}
     */
    private array $errors = [
        'iconSet' => [],
        'icon'    => [],
    ];

    /**
     * @param array<string,string> $old
     */
    public function __construct(
        private readonly array $old = [],
    ) {
    }

    /**
     * Adds given icon nodes to their icon set.
     */
    public function addIcon(Icon $icon): void
    {
        // if iconNode prefix in iconSets
        if (in_array($icon->getPrefix(), $this->getIconPrefixes(), true)) {
            $this->iconSets[$icon->getPrefix()]->addIcon($icon);
        } else {
            $this->iconSets[$icon->getPrefix()] = new IconSet($icon->getPrefix());
            $this->iconSets[$icon->getPrefix()]->addIcon($icon);
        }
    }

    /**
     * @return string[]
     */
    public function getIconPrefixes(): array
    {
        return array_keys($this->iconSets);
    }

    /**
     * @return array<string,IconSet>
     */
    public function getIconSets(): array
    {
        return $this->iconSets;
    }

    public function load(): void
    {
        foreach ($this->iconSets as $iconSet) {
            if ($iconSet->isFound()) {
                foreach ($iconSet->getIcons() as $icon) {
                    if ($icon->isFound()) {
                        $this->new[$icon->getKey()] = $icon->getSVG();
                    } else {
                        $this->addError('icon', new Error($icon));
                    }
                }
            } else {
                $this->addError('iconSet', new Error($iconSet));
            }
        }
    }

    /**
     * @return array<string,string>
     */
    public function getNew(): array
    {
        return $this->new;
    }

    /**
     * @return string[]
     */
    public function getAdded(): array
    {
        return array_diff(array_keys($this->new), array_keys($this->old));
    }

    /**
     * @return string[]
     */
    public function getRemoved(): array
    {
        return array_diff(array_keys($this->old), array_keys($this->new));
    }

    /**
     * @return array{scanned:int,icon-sets:array{int,int},icons:array{int,int},added:int,removed:int}
     */
    public function getStatistics(): array
    {
        $nodesCount = 0;
        $iconsCount = 0;
        foreach ($this->iconSets as $iconSet) {
            foreach ($iconSet->getIcons() as $icon) {
                $iconsCount++;
                $nodesCount += count($icon->getNodes());
            }
        }

        return [
            'scanned'   => $nodesCount,
            'icon-sets' => [count($this->getIconPrefixes()), count($this->errors['iconSet'])],
            'icons'     => [$iconsCount, count($this->errors['icon'])],
            'added'     => count($this->getAdded()),
            'removed'   => count($this->getRemoved()),
        ];
    }

    /**
     * @return array{iconSet:Error[],icon:Error[]}
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return $this->errors['icon'] !== [] || $this->errors['iconSet'] !== [];
    }

    /**
     * @param 'icon'|'iconSet' $type
     */
    private function addError(string $type, Error $error): void
    {
        $this->errors[$type][] = $error;
    }
}
