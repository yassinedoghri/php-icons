<?php

declare(strict_types=1);

$isSetup = false;

beforeEach(function () {
    global $isSetup;

    if (! $isSetup) {
        $this->clearIconsClass();

        $isSetup = true;
    }
});

describe('init', function () {
    it('can init PHPIcons', function () {
        expect(@file_get_contents(TEST_ICONS_FILE_PATH))
            ->toBeFalse(); // Icons.php does not exist yet

        $this->runPHPIconsInit();

        // Icons.php file is created and initialized with template
        expect(file_get_contents(TEST_ICONS_FILE_PATH))
            ->toBe(
                file_get_contents(
                    TEST_SRC_PATH . 'Console' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'IconsClass.template.php'
                )
            );
    });
});
