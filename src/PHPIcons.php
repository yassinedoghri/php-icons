<?php

declare(strict_types=1);

namespace PHPIcons;

use Exception;
use PHPIcons\Config\PHPIconsConfig;
use PHPIcons\Config\PHPIconsConfigBuilder;
use PHPIcons\Exceptions\IconNotFoundException;
use PHPIcons\Exceptions\LibraryMisuseException;

class PHPIcons implements \Stringable
{
    protected string $icon = '';

    protected string $prefix = '';

    /**
     * @var ?key-of<Icons::DATA>
     */
    protected ?string $iconKey = null;

    /**
     * @var array<string, string>
     */
    protected array $attributes = [];

    protected PHPIconsConfig $config;

    public function __construct(string $configFile = null)
    {
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $projectRoot = dirname((string) $reflection->getFileName(), 3);

        $configFile = $projectRoot . DIRECTORY_SEPARATOR . 'php-icons.php';

        if (file_exists($configFile)) {
            /** @var PHPIconsConfigBuilder $configBuilder */
            $configBuilder = require $configFile;
        } else {
            throw new Exception(sprintf('Config file %s was not found!', $configFile));
        }

        $this->config = new PHPIconsConfig();

        $configBuilder($this->config);
    }

    public function __toString(): string
    {
        if ($this->iconKey === null) {
            throw LibraryMisuseException::forUndefinedIcon();
        }

        $iconSVG = '';
        // check if icon has already been downloaded
        // @phpstan-ignore-next-line
        if (array_key_exists($this->iconKey, Icons::DATA)) {
            $iconSVG = Icons::DATA[$this->iconKey];
        } else { // icon was not found
            // check if there's a default to display for the given prefix
            if (array_key_exists($this->prefix, $this->config->getDefaultIconPerSet())) {
                $defaultIconKey = $this->config->getDefaultIconPerSet()[$this->prefix];
                if (array_key_exists($defaultIconKey, Icons::DATA)) {
                    $iconSVG = Icons::DATA[$defaultIconKey];
                }
            } elseif ($this->config->getDefaultIcon() !== null) {
                // no default for the prefix
                // check if there's a default icon set
                if (array_key_exists($this->config->getDefaultIcon(), Icons::DATA)) {
                    $iconSVG = Icons::DATA[$this->config->getDefaultIcon()];
                }
            }

            if ($iconSVG === '') {
                if ($this->config->getPlaceholder() === '') {
                    throw IconNotFoundException::forKeyNotFound($this->iconKey);
                }

                return '<span title="' . htmlspecialchars(
                    sprintf('"%s" icon not found.', $this->iconKey)
                ) . '">' . $this->config->getPlaceholder() . '</span>';
            }
        }

        if ($this->attributes !== []) {
            $iconSVG = $this->injectAttributes($iconSVG);
            $this->attributes = [];
        }

        $this->iconKey = null;

        return $iconSVG;
    }

    /**
     * @param string|key-of<Icons::DATA> $iconKey
     */
    public function icon(string $iconKey): self
    {
        if (! str_contains($iconKey, ':')) {
            if ($this->config->getDefaultPrefix() === '') {
                throw LibraryMisuseException::forMissingPrefix();
            }

            $this->prefix = $this->config->getDefaultPrefix();
            $this->icon = $iconKey;
        } else {
            $iconParts = explode(':', $iconKey);
            $this->prefix = $iconParts[0];
            $this->icon = $iconParts[1];
        }

        if ($this->icon === '') {
            throw LibraryMisuseException::forEmptyIcon();
        }

        if ($this->prefix === '') {
            throw LibraryMisuseException::forEmptyPrefix($this->icon);
        }

        $this->iconKey = $this->prefix . ':' . $this->icon;

        return $this;
    }

    /**
     * @param array<string, string> $attributes
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function attr(string $name, string $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    private function injectAttributes(string $iconSVG): string
    {
        $attributes = implode(
            ' ',
            array_map(fn ($key) => $key . '="' . $this->attributes[$key] . '"', array_keys($this->attributes))
        );

        return str_replace('<svg', '<svg ' . $attributes, $iconSVG);
    }
}
