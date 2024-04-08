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
    private bool $confirmOverwrite;

    public function __construct()
    {
        parent::__construct('init', 'Configure PHPIcons interactively');
    }

    public function interact(Interactor $io): void
    {
        $this->writer()
            ->eol();
        $this->writer()
            ->bold('Initializing PHPIcons', true);
    }

    public function execute(): int
    {
        // create Icons class if it doesn't exist
        if (! file_exists(dirname(__DIR__, 2) . '/Icons.php')) {
            $this->writer()
                ->info('Generating Icons class to cache icons SVGs… ');

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
                ->bold('Done!', true);
        }

        $this->confirmOverwrite = true;

        $configPathValidator = function ($value) {
            $dirExists = is_dir(dirname($value));

            if (! $dirExists) {
                throw new \InvalidArgumentException('Config file must live in a valid directory.');
            }

            if (! str_ends_with($value, '.php')) {
                throw new \InvalidArgumentException('Config file must end with .php extension.');
            }

            return $value;
        };

        $this->writer()
            ->eol();
        $this->set('configFile', $this->io()->prompt('Config file path', './php-icons.php', $configPathValidator, 5));

        // check that file doesn't already exist
        if (file_exists($this->configFile)) {
            $this->writer()
                ->eol();
            $this->confirmOverwrite = $this->io()
                ->confirm('Config file already exists, do you want to overwrite it?', 'n');
        }

        if (! $this->confirmOverwrite) {
            return 0;
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
            ->ok(sprintf('[OK] PHPIcons initialized in %s', realpath($this->configFile)), true);
        $this->writer()
            ->eol();

        return 0;
    }
}
