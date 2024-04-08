<?php

declare(strict_types=1);

namespace PHPIcons\Config;

class PHPIconsConfigBuilder
{
    /**
     * @var string[]
     */
    private array $apiHosts = ['https://api.iconify.design', 'https://api.simplesvg.com', 'https://api.unisvg.com'];

    /**
     * @var string[]
     */
    private array $paths = [];

    /**
     * @var array<string,string>
     */
    private array $localIconSets = [];

    private string $defaultPrefix = '';

    private string $placeholder = '�';

    /**
     * @var string[]
     */
    private array $identifiers = ['icon'];

    public function __invoke(PHPIconsConfig $phpIconsConfig): void
    {
        $phpIconsConfig->setApiHosts($this->apiHosts);

        $phpIconsConfig->setLocalIconSets($this->localIconSets);

        $phpIconsConfig->setPaths($this->paths);

        $phpIconsConfig->setDefaultPrefix($this->defaultPrefix);

        $phpIconsConfig->setIdentifiers($this->identifiers);

        $phpIconsConfig->setPlaceholder($this->placeholder);
    }

    /**
     * @param string|string[] $apiHosts
     */
    public function withApiHosts(string|array $apiHosts): self
    {
        $this->apiHosts = is_array($apiHosts) ? $apiHosts : explode(',', $apiHosts);

        return $this;
    }

    /**
     * @param string[] $paths
     */
    public function withPaths(array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    public function withDefaultPrefix(string $defaultPrefix): self
    {
        $this->defaultPrefix = $defaultPrefix;

        return $this;
    }

    /**
     * @param string|string[] $identifiers
     */
    public function withIdentifiers(string|array $identifiers): self
    {
        $this->identifiers = is_array($identifiers) ? $identifiers : explode(',', $identifiers);

        return $this;
    }

    public function withPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @param array<string,string> $localIconSets
     */
    public function withLocalIconSets(array $localIconSets): self
    {
        $this->localIconSets = $localIconSets;

        return $this;
    }
}
