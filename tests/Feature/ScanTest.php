<?php

declare(strict_types=1);

$isSetup = false;

beforeEach(function () {
    global $isSetup;

    if (! $isSetup) {
        $this->clearIconsClass();
        $this->clearTempViews();

        $this->runPHPIconsInit();

        $isSetup = true;
    }
});

describe('scan', function () {
    it(
        'can scan PHP files and load icons',
        function () {
            $this->copyViewFileToTemp('fakeView.php');

            $this->runPHPIconsScan();

            expect(file_get_contents(TEST_ICONS_FILE_PATH))
                ->toBe(file_get_contents(TEST_SNAPSHOTS_PATH . 'Icons_first_add.php'));
        }
    );

    it('can add new icons', function () {
        $this->copyViewFileToTemp('fakeView2.php');

        $this->runPHPIconsScan();

        expect(file_get_contents(TEST_ICONS_FILE_PATH))
            ->toBe(file_get_contents(TEST_SNAPSHOTS_PATH . 'Icons_second_add.php'));
    });

    it('can remove icons', function () {
        unlink(TEST_TEMP_VIEWS_PATH . 'fakeView.php');

        $this->runPHPIconsScan();

        expect(file_get_contents(TEST_ICONS_FILE_PATH))
            ->toBe(file_get_contents(TEST_SNAPSHOTS_PATH . 'Icons_after_delete.php'));
    });
});
