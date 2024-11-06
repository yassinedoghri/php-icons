<?php

declare(strict_types=1);

use PHPIcons\PHPIcons;

$phpIcon = new PHPIcons(TEST_FIXTURES_PATH . 'php-icons.php');
PHPIconsSingleton::setInstance($phpIcon);

describe('icon function', function () {
    it('can use a custom PHPIcons instance', function () {
        $phpIcon = new PHPIcons(TEST_FIXTURES_PATH . 'php-icons.php');
        $phpIcon2 = icon('foo:bar');

        expect(spl_object_id($phpIcon))
            ->not->toBe(spl_object_id($phpIcon2));

        PHPIconsSingleton::setInstance($phpIcon);
        $phpIcon3 = icon('bar:baz');

        expect(spl_object_id($phpIcon))
            ->toBe(spl_object_id($phpIcon3));
    });

    it('returns same PHPIcons instance each time', function () {
        $phpIcons1 = icon('foo:bar');
        $phpIcons2 = icon('foo:bar');

        expect(spl_object_id($phpIcons1))
            ->toBe(spl_object_id($phpIcons2));
    });
});
