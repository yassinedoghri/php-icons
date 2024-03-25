# PHP Iconify 🐘 🙂

[![Latest Stable Version](http://poser.pugx.org/yassinedoghri/php-iconify/v)](https://packagist.org/packages/yassinedoghri/php-iconify)
[![Total Downloads](http://poser.pugx.org/yassinedoghri/php-iconify/downloads)](https://packagist.org/packages/yassinedoghri/php-iconify)
[![Latest Unstable Version](http://poser.pugx.org/yassinedoghri/php-iconify/v/unstable)](https://packagist.org/packages/yassinedoghri/php-iconify)
[![License](https://img.shields.io/github/license/yassinedoghri/php-iconify?color=green)](https://packagist.org/packages/yassinedoghri/php-iconify)
[![PHP Version Require](http://poser.pugx.org/yassinedoghri/php-iconify/require/php)](https://packagist.org/packages/yassinedoghri/php-iconify)

A PHP library based on [iconify's API](https://iconify.design/) to download and render svg icons from [popular open source icon sets](https://icon-sets.iconify.design/).

## ❓ How does it work?

On first request, PHP Iconify will download the requested icon using Iconify's API, cache it into the defined `icons_folder` and render it.\
Subsequent requests will load the icon directly from the `icons_folder` and render it.

## 🚀 Getting started

### 1. Install via composer

```sh
composer require yassinedoghri/php-iconify
```

### 2. Usage

```php
use PHPIconify\Iconify;

$options = [
        // icons are cached in `./php-iconify` folder by default
        'icons_folder' => './php-iconify'
];

$iconify = new Iconify($options);

echo $iconify->icon('material-symbols:bolt');
// <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
//      <path fill="currentColor" d="m8 22l1-7H4l9-13h2l-1 8h6L10 22z"/>
// </svg>
```

👉 **You can add custom attributes** using the `attr()` method, such as classes and styles:

```php
echo $iconify
        ->icon('material-symbols:bolt')
        ->attr('class', 'text-2xl')
        ->attr('style', 'color: yellow;');
// <svg class="text-2xl" style="color: yellow;" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
//      <path fill="currentColor" d="m8 22l1-7H4l9-13h2l-1 8h6L10 22z"/>
// </svg>
```

## ⚙️ Configuration

The Iconify class takes in options which you can tweak as you please:

| option         | type       | description                                                                                                         | default                                                                                                                                                                    |
| -------------- | ---------- | ------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `api_hosts`    | `string[]` | Iconify API hosts to call for downloading svg icons. Starts by querying the first host, the rest is used as backup. | Defaults to [Iconify's public hosts](https://iconify.design/docs/api/#public-api): `['https://api.iconify.design', 'https://api.simplesvg.com', 'https://api.unisvg.com']` |
| `icons_folder` | `string`   | Folder in which to cache icons.                                                                                     | `./php-iconify`                                                                                                                                                            |

## ❤️ Acknowledgments

This wouldn't have been possible without the awesome work from the [Iconify](https://iconify.design/) team and designers that maintain [the many open source icon sets](https://icon-sets.iconify.design/).

Inspired by [astro-icon](https://www.astroicon.dev/).

## 📜 License

Code released under the [MIT License](https://choosealicense.com/licenses/mit/).

Copyright (c) 2024-present, Yassine Doghri ([@yassinedoghri](https://yassinedoghri.com/)).
