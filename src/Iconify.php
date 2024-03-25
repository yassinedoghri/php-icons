<?php

declare(strict_types=1);

namespace PHPIconify;

use Exception;
use PHPIconify\Exceptions\IconNotFoundException;
use PHPIconify\Exceptions\PHPIconifyMisuseException;

class Iconify implements \Stringable
{
    protected string $icon = '';

    protected string $pack = '';

    protected string $iconPath = '';

    /**
     * @var array<string, string>
     */
    protected array $attributes = [];

    /**
     * @var array{api_hosts:string[],icons_folder:string}
     */
    protected array $options = [
        'api_hosts'    => ['https://api.iconify.design', 'https://api.simplesvg.com', 'https://api.unisvg.com'],
        'icons_folder' => './php-iconify',
    ];

    /**
     * @param ?array{api_hosts:string[],icons_folder:string} $options
     */
    public function __construct(?array $options = null)
    {
        if ($options !== null) {
            $this->options = array_merge($this->options, $options);
        }
    }

    public function __toString(): string
    {
        if ($this->iconPath === '') {
            throw PHPIconifyMisuseException::forUndefinedIcon();
        }

        // check if icon has already been downloaded
        if (file_exists($this->iconPath)) {
            $iconSVG = file_get_contents($this->iconPath);

            if (! $iconSVG) {
                throw IconNotFoundException::forFileNotFound($this->iconPath);
            }
        } else {
            // icon does not exist, get it from iconify
            $iconSVG = $this->getSVGFromAPI();

            // save the icon to iconsPath
            file_put_contents($this->iconPath, $iconSVG);
        }

        if ($this->attributes !== []) {
            $iconSVG = $this->injectAttributes($iconSVG);
        }

        return $iconSVG;
    }

    public function icon(string $iconifyIcon): self
    {
        if (! str_contains($iconifyIcon, ':')) {
            throw PHPIconifyMisuseException::forWrongIconFormat();
        }

        $iconParts = explode(':', $iconifyIcon);
        $this->pack = $iconParts[0];
        $this->icon = $iconParts[1];

        if ($this->icon === '') {
            throw PHPIconifyMisuseException::forEmptyIcon();
        }

        if ($this->pack === '') {
            throw PHPIconifyMisuseException::forEmptyIconPack($this->icon);
        }

        $directory = sprintf('%s/%s', $this->options['icons_folder'], $this->pack);
        $iconDirectory = realpath($directory);

        if (! $iconDirectory) {
            // icon directory does not exist, initialize it
            $this->initIconDirectory($directory);
        }

        $this->iconPath = sprintf('%s' . DIRECTORY_SEPARATOR . '%s.svg', $iconDirectory, $this->icon);

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

    private function initIconDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    private function getSVGFromAPI(): string
    {
        if ($this->options['api_hosts'] === []) {
            throw PHPIconifyMisuseException::forMissingAPIHosts();
        }

        foreach ($this->options['api_hosts'] as $host) {
            $svgIcon = file_get_contents(sprintf('%s/%s/%s.svg', $host, $this->pack, $this->icon));

            if ($svgIcon === '404') {
                throw IconNotFoundException::forHostReturned404($host, $this->pack, $this->icon);
            }

            if ($svgIcon === false) {
                continue;
            }

            return $svgIcon;
        }

        throw new Exception(sprintf('Could not retrieve svg icon "%s:%s" from hosts.', $this->pack, $this->icon));
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
