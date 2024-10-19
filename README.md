<div align="center">

# PHPIcons 🐘 🙂

**A convenient PHP library to render svg icons.**

[![Latest Stable Version](https://poser.pugx.org/yassinedoghri/php-icons/v)](https://packagist.org/packages/yassinedoghri/php-icons)
[![Total Downloads](https://poser.pugx.org/yassinedoghri/php-icons/downloads)](https://packagist.org/packages/yassinedoghri/php-icons)
[![codecov](https://codecov.io/gh/yassinedoghri/php-icons/graph/badge.svg?token=P8CG7J5HOQ)](https://codecov.io/gh/yassinedoghri/php-icons)
[![License](https://img.shields.io/github/license/yassinedoghri/php-icons?color=green)](https://packagist.org/packages/yassinedoghri/php-icons)
[![PHP Version Require](https://poser.pugx.org/yassinedoghri/php-icons/require/php)](https://packagist.org/packages/yassinedoghri/php-icons)

</div>

Get access to over 200,000 icons from more than
[150 open source icon sets](https://icon-sets.iconify.design/) directly from
your php files!

Thanks to [Iconify](https://iconify.design/) ❤️

## 🧩 Integrations

| Framework                                | Home                                                                                  | Description                                                                                                                               |
| ---------------------------------------- | ------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| [CodeIgniter4](https://codeigniter.com/) | [yassinedoghri/codeigniter-icons](https://github.com/yassinedoghri/codeigniter-icons) | A CodeIgniter4 library with convenient helper functions to render svg icons using [php-icons](https://github.com/yassinedoghri/php-icons) |
| [Tempest](https://tempestphp.com/)       | [yassinedoghri/tempest-icons](https://github.com/yassinedoghri/tempest-icons)         | A Tempest library providing a convenient `icon(…)` function for rendering SVG icons with php-icons.                                       |

## 🚀 Getting started

### 1. Install via composer

```sh
composer require yassinedoghri/php-icons
```

### 2. Configure

Run the following command to initialize the configuration file:

```sh
vendor/bin/php-icons init
```

This will prompt you to create a `php-icons.php` config file in the root of your
project. See [config reference](#⚙️-config-reference) for more info.

### 3. Use anywhere

#### 3.1. `icon(string $iconKey)` method

Use the `icon` method in your view files with the icon key string
(`{prefix}:{icon}`) as parameter:

- `{prefix}`: is the
  [icon set prefix](https://iconify.design/docs/icons/icon-set-basics.html#naming)
- `{name}`: is the
  [icon name](https://iconify.design/docs/icons/icon-basics.html#icon-names)

```php
<?php

use PHPIcons\PHPIcons;

$phpicons = new PHPIcons('/path/to/config/file.php');

echo $phpicons->icon('material-symbols:bolt');
// <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
//      <path fill="currentColor" d="m8 22l1-7H4l9-13h2l-1 8h6L10 22z"/>
// </svg>
```

👉 add any attribute using the `attr()` or `attributes()` methods:

```php
echo $phpicons
        ->icon('material-symbols:bolt')
        ->attr('class', 'text-2xl')
        ->attr('style', 'color: yellow;');
// <svg class="text-2xl" style="color: yellow;" […]>…</svg>

echo $phpicons
        ->icon('material-symbols:bolt')
        ->attributes([
          'class' => 'text-2xl',
          'style' => 'color: yellow;'
        ]);
// <svg class="text-2xl" style="color: yellow;" […]>…</svg>
```

> [!TIP]  
> Find and copy the icon keys of popular open source icon sets from
> [Iconify's index](https://icon-sets.iconify.design/).

#### 3.2. Scan source files and load icons

> [!IMPORTANT]  
> When first defining icons, a placeholder (`�` by default) will be displayed.\
> Make sure to run the `scan` command to load the SVGs.

```sh
vendor/bin/php-icons scan
```

## ⚙️ Config reference

Your config file is used by both the `php-icons` CLI tool and PHPIcons class, it
should look like this:

```php
<?php

declare(strict_types=1);

use PHPIcons\Config\PHPIconsConfig;

return PHPIconsConfig::configure()
    ->withPaths([
      __DIR__ . '/src'
    ])
    ->withDefaultPrefix('')
    ->withPlaceholder('�');
```

### Paths

`withPaths([])`

List of paths to your source files. PHP files will be parsed and scanned for
discovering the icons you have defined.

### API Hosts

`withAPIHosts([])`

[Iconify API](https://iconify.design/docs/api/) hosts to query for downloading
svg icons. Starts by querying the first host, the rest is used as backup.

Defaults to Iconify's public hosts:
`["https://api.iconify.design","https://api.simplesvg.com", "https://api.unisvg.com"]`

### Local Icon Sets

`withLocalIconSets([])`

If you have custom icons, php-icons can look them up locally in your file system
instead of calling for the [Iconify API](https://iconify.design/docs/api/).

> [!IMPORTANT]  
> php-icons will look for `{name}.svg` files in your local icon sets

Takes in an associative array with the icon set prefix as the key and its path
as value.

#### Example

```
my-custom-set/
├── heart.svg
├── rocket.svg
├── star.svg
└── user.svg
```

```php
// in your config file
->withLocalIconSets([
  'custom' => '/path/to/my-custom-set',
])
```

```php
// ✅ ALL GOOD
echo $phpicons->icon('custom:heart');
echo $phpicons->icon('custom:rocket');
echo $phpicons->icon('custom:star');
echo $phpicons->icon('custom:user');

// ❌ ICONS NOT FOUND
echo $phpicons->icon('custom:banana');
echo $phpicons->icon('custom:key');
```

### Default Prefix

`withDefaultPrefix('')`

Default icon set prefix to use when none is set.

#### Example

With `material-symbols` set as default prefix:

```php
// this
echo $phpicons->icon('bolt');

// same as this
echo $phpicons->icon('material-symbols:bolt');
```

### Default Icon

`withDefaultIcon()`

Default icon to use when an icon has not been found.

Takes in an icon key `{prefix}:{name}`. If a prefix is not set, the default
prefix will be used instead.

### Default Icon Per Set

`withDefaultIconPerSet([])`

Default icon to use when an icon has not been found in a set.

Takes in an associative array, with the key being the icon set prefix, and the
value being the default icon.

### Placeholder

`withPlaceholder('�')`

String to show when icon is not found or unknown.

Defaults to `�` (REPLACEMENT CHARACTER).

### Identifiers

`withIdentifiers([])`

Function or method names to match for identifying icon keys in your source
files.

Defaults to `['icon']`.

## 🖥️ CLI commands

```sh
> vendor/bin/php-icons

         _             _
   _ __ | |__  _ __   (_) ___ ___  _ __  ___
  | '_ \| '_ \| '_ \  | |/ __/ _ \| '_ \/ __|
  | |_) | | | | |_) | | | (_| (_) | | | \__ \
  | .__/|_| |_| .__/  |_|\___\___/|_| |_|___/
  |_|         |_|

 A convenient PHP library to render svg icons
----------------------------------------------

PHPIcons, version 1.0.0.0-dev

Commands:
*
  init i    Configure PHPIcons interactively
  scan s    Scans source files and loads icons

Run `<command> --help` for specific help
```

## ❤️ Acknowledgments

This wouldn't have been possible without the awesome work from the
[Iconify](https://iconify.design/) team and designers that maintain
[the many open source icon sets](https://icon-sets.iconify.design/).

Inspired by [astro-icon](https://www.astroicon.dev/),
[blade-icons](https://blade-ui-kit.com/blade-icons) and
[rector](https://getrector.com/).

## 📜 License

Code released under the [MIT License](https://choosealicense.com/licenses/mit/).

Copyright (c) 2024-present, Yassine Doghri
([@yassinedoghri](https://yassinedoghri.com/)).
