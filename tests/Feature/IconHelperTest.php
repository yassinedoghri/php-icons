<?php

declare(strict_types=1);

PHPIconsSingleton::setInstance(TEST_FIXTURES_PATH . 'php-icons.php');

describe('icon function', function () {
    it('returns same PHPIcons instance each time', function () {
        $phpIcons1 = icon('luciole:check');
        $phpIcons2 = icon('luciole:check');

        expect(spl_object_id($phpIcons1))
            ->toBe(spl_object_id($phpIcons2));
    });
});
