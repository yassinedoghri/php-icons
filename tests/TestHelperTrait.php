<?php

declare(strict_types=1);

namespace Tests;

use Ahc\Cli\IO\Interactor;
use Exception;
use PHPIcons\Console\Commands\InitCommand;
use PHPIcons\Console\Commands\ScanCommand;

// @phpstan-ignore trait.unused
trait TestHelperTrait
{
    public function clearIconsClass(): void
    {
        // add @ to discard "No such file or directory" warning if file does not exist
        @unlink(TEST_ICONS_FILE_PATH);
    }

    public function clearTempViews(?string $folder = null): void
    {
        $tempViewFiles = $folder === null ? glob(TEST_TEMP_VIEWS_PATH . '*') : glob(rtrim($folder, '/') . '/*');

        foreach ($tempViewFiles as $viewFile) {
            if (is_dir($viewFile)) {
                $this->clearTempViews($viewFile);
                rmdir($viewFile);
            }

            if (str_ends_with($viewFile, '.php')) {
                // add @ to discard "No such file or directory" warning if file does not exist
                @unlink($viewFile);
            }
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

    public function copyViewFileToTemp(string $viewFile, ?string $destination = null): void
    {
        if ($destination === null) {
            $destination = $viewFile;
        }

        $destinationFile = TEST_TEMP_VIEWS_PATH . $destination;
        $directory = dirname($destinationFile);
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        copy(TEST_VIEWS_PATH . $viewFile, $destinationFile);
    }

    public function copyIconsSnapshot(string $snapshot): void
    {
        if (! file_exists(TEST_SNAPSHOTS_PATH . $snapshot)) {
            throw new Exception(sprintf('Snapshot file %s does not exist.', TEST_SNAPSHOTS_PATH . $snapshot), 1);
        }

        copy(TEST_SNAPSHOTS_PATH . $snapshot, TEST_SRC_PATH . 'Icons.php');
    }
}
