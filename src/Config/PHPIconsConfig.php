<?php

declare(strict_types=1);

namespace PHPIcons\Config;

use Exception;

class PHPIconsConfig
{
    /**
     * @var string[]
     */
    private array $apiHosts = [];

    /**
     * @var string[]
     */
    private array $paths = [];

    /**
     * @var array<string, string>
     */
    private array $localIconSets = [];

    private string $defaultPrefix = '';

    /**
     * @var string[]
     */
    private array $identifiers = [];

    private string $placeholder = '';

    private ?string $defaultIcon = null;

    /**
     * @var array<string,string>
     */
    private array $defaultIconPerSet = [];

    public static function configure(): PHPIconsConfigBuilder
    {
        return new PHPIconsConfigBuilder();
    }

    /**
     * @param string[] $apiHosts
     */
    public function setApiHosts(array $apiHosts): void
    {
        if ($apiHosts === []) {
            throw new Exception('API Hosts array must not be empty!');
        }

        $filteredHosts = [];
        foreach ($apiHosts as $host) {
            if (! filter_var($host, FILTER_VALIDATE_URL)) {
                throw new Exception(sprintf('Host %s is not a valid url!', $host));
            }

            // remove trailing slashes
            $filteredHosts[] = rtrim($host, '/');
        }

        $this->apiHosts = $filteredHosts;
    }

    /**
     * @param array<string,string> $localIconSets
     */
    public function setLocalIconSets(
        array $localIconSets
    ): void {
        foreach ($localIconSets as $prefix => $path) {
            if (! is_dir($path)) {
                throw new Exception(sprintf('Local icon set "%s" directory not found in %s', $prefix, $path));
            }
        }

        $this->localIconSets = $localIconSets;
    }

    /**
     * @param string[] $paths
     */
    public function setPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if (! is_dir($path) && ! is_file($path)) {
                throw new Exception(sprintf('Path "%s" is not a directory nor a file.', $path));
            }
        }

        $this->paths = $paths;
    }

    public function setDefaultPrefix(string $defaultPrefix): void
    {
        $this->defaultPrefix = $defaultPrefix;
    }

    public function setDefaultIcon(?string $defaultIcon): void
    {
        // add default prefix if none is set
        if ($defaultIcon !== null && ! str_contains($defaultIcon, ':')) {
            $defaultIcon = $this->defaultPrefix . ':' . $defaultIcon;
        }

        $this->defaultIcon = $defaultIcon;
    }

    /**
     * @param array<string,string> $defaultIconPerSet
     */
    public function setDefaultIconPerSet(
        array $defaultIconPerSet
    ): void {
        foreach ($defaultIconPerSet as $prefix => $defaultIcon) {
            // add default prefix if none is set
            if (! str_contains($defaultIcon, ':')) {
                $defaultIconPerSet[$prefix] = $this->defaultPrefix . ':' . $defaultIcon;
            }
        }

        $this->defaultIconPerSet = $defaultIconPerSet;
    }

    /**
     * @param string[] $identifiers
     */
    public function setIdentifiers(array $identifiers): void
    {
        $this->identifiers = $identifiers;
    }

    public function setPlaceholder(string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return string[]
     */
    public function getApiHosts(): array
    {
        return $this->apiHosts;
    }

    /**
     * @return array<string, string>
     */
    public function getLocalIconSets(): array
    {
        return $this->localIconSets;
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    public function getDefaultPrefix(): string
    {
        return $this->defaultPrefix;
    }

    public function getDefaultIcon(): ?string
    {
        return $this->defaultIcon;
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultIconPerSet(): array
    {
        return $this->defaultIconPerSet;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
