<?php

declare(strict_types=1);

namespace Tests;

use Ahc\Cli\IO\Interactor;
use Exception;
use PHPIcons\Console\Commands\InitCommand;
use PHPIcons\Console\Commands\ScanCommand;

trait TestHelperTrait
{
    public function clearIconsClass(): void
    {
        // add @ to discard "No such file or directory" warning if file does not exist
        @unlink(TEST_ICONS_FILE_PATH);
    }

    public function clearTempViews(): void
    {
        $tempViewFiles = glob(TEST_TEMP_VIEWS_PATH . '*.php') ?? [];
        foreach ($tempViewFiles as $viewFile) {
            // add @ to discard "No such file or directory" warning if file does not exist
            @unlink($viewFile);
        }
    }

    public function runPHPIconsInit(): void
    {
        // Initialize the PHPIcons configuration
        $init = new InitCommand();
        $init->interact(new Interactor());
        $init->execute();
    }

    public function runPHPIconsScan(): void
    {
        // Create the ScanCommand instance and execute it
        $scan = new ScanCommand();
        $scan->interact(new Interactor());
        $scan->execute();
    }

    public function copyViewFileToTemp(string $viewFile): void
    {
        copy(TEST_VIEWS_PATH . $viewFile, TEST_TEMP_VIEWS_PATH . $viewFile);
    }

    public function copyIconsSnapshot(string $snapshot): void
    {
        if (! file_exists(TEST_SNAPSHOTS_PATH . $snapshot)) {
            throw new Exception(sprintf('Snapshot file %s does not exist.', TEST_SNAPSHOTS_PATH . $snapshot), 1);
        }

        copy(TEST_SNAPSHOTS_PATH . $snapshot, TEST_SRC_PATH . 'Icons.php');
    }
}
