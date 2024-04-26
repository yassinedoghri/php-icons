<?php

declare(strict_types=1);

namespace PHPIcons\Console\Commands;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;

/**
 * @property string $configFile
 */
class InitCommand extends Command
{
    public function __construct()
    {
        parent::__construct('init', 'Configure PHPIcons interactively');

        $this->option('-c --config-file', 'Config file path', null, CLIENT_ROOTPATH . 'php-icons.php');
    }

    public function interact(Interactor $io): void
    {
        $this->writer()
            ->eol();
        $this->writer()
            ->bold('Initializing PHPIcons');
        $this->writer()
            ->eol(2);
    }

    public function execute(): int
    {
        $this->writer()
            ->info('Generating Icons class to cache icons…');
        // create Icons class if it doesn't exist
        if (! file_exists(dirname(__DIR__, 2) . '/Icons.php')) {
            if (! copy(
                dirname(__DIR__, 1) . '/Templates/IconsClass.template.php',
                dirname(__DIR__, 2) . '/Icons.php'
            )) {
                $this->writer()
                    ->eol();
                $this->writer()
                    ->error('Oops… Something wrong happened when copying Icons template!', true);

                return 2;
            }

            $this->writer()
                ->ok(' done!');
        } else {
            $this->writer()
                ->bold(' skipped!', true);
            $this->writer()
                ->white('Icons class already exists.');
        }

        $this->writer()
            ->eol(2);
        $this->writer()
            ->info(sprintf('Generating config file (%s)…', $this->configFile));
        // check that file doesn't already exist
        if (file_exists($this->configFile)) {
            $this->writer()
                ->bold(' skipped!', true);
            $this->writer()
                ->white('Config file already exists.');
            $this->writer()
                ->eol(2);

            return 0;
        }

        $this->writer()
            ->eol();

        // check that config file path is ok
        $dirExists = is_dir(dirname($this->configFile));

        if (! $dirExists) {
            throw new \InvalidArgumentException('Config file must live in a valid directory.');
        }

        if (! str_ends_with($this->configFile, '.php')) {
            throw new \InvalidArgumentException('Config file must end with .php extension.');
        }

        $configTemplate = file_get_contents(__DIR__ . '/../Templates/Config.template.php');

        if (! $configTemplate) {
            $this->writer()
                ->eol();
            $this->writer()
                ->error('[KO] Error when getting config template', true);
            $this->writer()
                ->eol();

            return 2;
        }

        $this->writer()
            ->eol();
        /** @var string $defaultPrefix */
        $defaultPrefix = $this->io()
            ->prompt('Set a default prefix (hit ENTER for none)', '', null, 0);

        $result = file_put_contents(
            $this->configFile,
            str_replace("'/** DEFAULT_PREFIX **/'", "'{$defaultPrefix}'", $configTemplate)
        );

        if (! $result) {
            $this->writer()
                ->eol();
            $this->writer()
                ->error(sprintf('[KO] Error when writing config file %s', $this->configFile), true);
            $this->writer()
                ->eol();

            return 2;
        }

        $this->writer()
            ->eol();
        $this->writer()
            ->ok(sprintf('[OK] PHPIcons initialized with config %s', realpath($this->configFile)), true);
        $this->writer()
            ->eol();

        return 0;
    }
}
