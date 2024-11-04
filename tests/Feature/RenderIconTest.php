<?php

declare(strict_types=1);

use PHPIcons\Exceptions\LibraryMisuseException;
use PHPIcons\PHPIcons;

$setup = false;

beforeEach(function () {
    global $setup;

    if (! $setup) {
        $this->clearIconsClass();
        $this->clearTempViews();

        $this->copyViewFileToTemp('fakeView.php');

        $this->copyIconsSnapshot('Icons_first_add.php');

        $setup = true;
    }
});

describe('render icon', function () {
    $phpIcons = new PHPIcons(TEST_FIXTURES_PATH . 'php-icons.php');

    it('throws with no icon key set', function () use (&$phpIcons) {
        return (string) $phpIcons;
    })->throws(LibraryMisuseException::class);

    it('throws with empty icon key', function () use (&$phpIcons) {
        return (string) $phpIcons->icon('');
    })->throws(LibraryMisuseException::class);

    it('throws without icon key prefix', function () use (&$phpIcons) {
        return (string) $phpIcons->icon('foo');
    })->throws(LibraryMisuseException::class);

    it('throws empty icon name', function () use (&$phpIcons) {
        return (string) $phpIcons->icon('ri:');
    })->throws(LibraryMisuseException::class);

    it('throws empty prefix', function () use (&$phpIcons) {
        return (string) $phpIcons->icon(':bar');
    })->throws(LibraryMisuseException::class);

    it('renders icon not found', function () use (&$phpIcons) {
        expect((string) $phpIcons->icon('foo:bar'))
            ->toBe('<span title="&quot;foo:bar&quot; icon not found.">�</span>');
    });

    it('renders icon', function () use (&$phpIcons) {
        expect((string) $phpIcons->icon('lucide:check'))
            ->toBe(
                '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/></svg>'
            );
    });

    it('renders with attributes', function () use (&$phpIcons) {
        expect((string) $phpIcons->icon('lucide:check')->attributes([
            'class' => 'text-xl',
            'style' => 'color: green;',
        ]))->toBe(
            '<svg class="text-xl" style="color: green;" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/></svg>'
        );
    });

    it('renders with attr', function () use (&$phpIcons) {
        expect((string) $phpIcons->icon('lucide:check')->attr('class', 'text-xl')->attr('style', 'color: green;'))
            ->toBe(
                '<svg class="text-xl" style="color: green;" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/></svg>'
            );
    });
});
