<?php

declare(strict_types=1);

use Ahc\Cli\IO\Interactor;
use PHPIcons\Console\Commands\InitCommand;
use PHPIcons\Console\Commands\ScanCommand;
use PHPIcons\Icons;
use Tests\TestCase;

it('can init PHPIcons, scan PHP files and load icons', function () {

    define('CLIENT_ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR);	

    // Initialize the PHPIcons configuration
    $init = new InitCommand();
    $init->interact(new Interactor());
    $init->execute();

    // Create the ScanCommand instance and execute it
    $scan = new ScanCommand();
    $scan->interact(new Interactor());
    $scan->execute();

    expect(Icons::DATA)->toBe([
        'lucide:copy' => '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></g></svg>',
        'lucide:check' => '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/></svg>',
        'lucide:airplay' => '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"/><path d="m12 15l5 6H7Z"/></g></svg>',
      ]);
});