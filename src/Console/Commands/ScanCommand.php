<?php

declare(strict_types=1);

namespace PHPIcons\Console\Commands;

use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use Exception;
use PHPIcons\Config\PHPIconsConfig;
use PHPIcons\Config\PHPIconsConfigBuilder;
use PHPIcons\Console\IconData;
use PHPIcons\Console\IconSet;
use PHPIcons\Console\Visitors\IconsAnnotationsVisitor;
use PHPIcons\Console\Visitors\IconsFunctionsVisitor;
use PHPIcons\Icons;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

/**
 * @property ?string[] $paths
 * @property ?string $defaultPrefix
 * @property ?string $apiHosts
 * @property ?string $identifiers
 * @property string $configFile
 * @property bool $dryRun
 */
class ScanCommand extends Command
{
    private PHPIconsConfig $config;

    private IconData $iconData;

    public function __construct()
    {
        parent::__construct('scan', 'Scans source files and loads icons');

        $this
            ->arguments('[paths...]')
            ->option('-p --default-prefix', 'Default icon set prefix')
            ->option('-a --api-hosts', 'API Hosts. Defaults to Iconify’s public api hosts')
            ->option('-c --config-file', 'Config file path', null, CLIENT_ROOTPATH . 'php-icons.php')
            ->option('-i --identifiers', 'Function or Method identifiers to detect icon keys"')
            ->option('-d --dry-run', 'Only scan source files and show diff', 'boolval', false);
    }

    // This method is auto called before `self::execute()` and receives `Interactor $io` instance
    public function interact(Interactor $io): void
    {
        $this->writer()
            ->eol();

        // check that config file exists to load it
        $configBuilder = PHPIconsConfig::configure();

        // if false, then file does not exist
        if (file_exists($this->configFile)) {
            /** @var PHPIconsConfigBuilder $configBuilder */
            $configBuilder = require $this->configFile;

            $this->writer()
                ->info("Using configuration file {$this->configFile}", true);
        } else {
            $this->writer()
                ->warn("Config file {$this->configFile} not found. Using defaults.", true);
        }

        if ($this->apiHosts) {
            $configBuilder->withApiHosts($this->apiHosts);
        }

        if ($this->paths) {
            $configBuilder->withPaths($this->paths);
        }

        if ($this->defaultPrefix) {
            $configBuilder->withDefaultPrefix($this->defaultPrefix);
        }

        if ($this->identifiers) {
            $configBuilder->withIdentifiers($this->identifiers);
        }

        $this->config = new PHPIconsConfig();

        $configBuilder($this->config);

        $this->iconData = new IconData(Icons::DATA);
    }

    public function execute(): int
    {
        $filesToScan = $this->getPHPFilesToScan();

        foreach ($filesToScan as $file) {
            $this->extractIconsFromPHPFile($file);
        }

        foreach ($this->iconData->getIconSets() as $prefix => $iconSet) {
            if (in_array($prefix, array_keys($this->config->getLocalIconSets()), true)) {
                $this->getIconsDataFromLocal($iconSet);
            } else {
                $this->getIconsDataFromAPI($iconSet);
            }
        }

        $this->iconData->load();

        $this->renderErrors();

        $this->renderDiff();

        if (! $this->dryRun) {
            $this->writeIcons();
        }

        $this->renderSummary();

        if ($this->iconData->hasErrors()) {
            $this->writer()
                ->eol();
            $this->writer()
                ->bgRed('[KO] Check errors!', true);

            $this->writer()
                ->eol();
            return 2;
        }

        // finished without any error
        $this->writer()
            ->eol();
        $this->writer()
            ->bgGreen('[OK] No errors!', true);

        $this->writer()
            ->eol();
        return 0;
    }

    private function getIconsDataFromLocal(IconSet $iconSet): void
    {
        $localPath = $this->config->getLocalIconSets()[$iconSet->getPrefix()];

        foreach ($iconSet->getIconNames() as $iconName) {
            $contents = file_get_contents($localPath . '/' . $iconName . '.svg');

            if ($contents !== false) {
                $iconSet->setIconFound($iconName, $contents);
            }
        }

        $iconSet->setFound();
    }

    private function getIconsDataFromAPI(IconSet $iconSet): void
    {
        foreach ($this->config->getApiHosts() as $host) {
            $ch = curl_init();

            curl_setopt(
                $ch,
                CURLOPT_URL,
                sprintf('%s/%s.json?icons=%s', $host, $iconSet->getPrefix(), implode(',', $iconSet->getIconNames()))
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            /** @var string|false $response */
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 404 || $response === '404') {
                // icon set not found
                break;
            }

            if (curl_errno($ch) !== 0) {
                // issue with host, continue to backups
                $this->writer()
                    ->error(curl_errno($ch) . ': ' . curl_error($ch), true);
                continue;
            }

            if (! $response) {
                // unexpected, should continue
                continue;
            }

            /** @var ?array{prefix:string,lastModified:int,width?:int,height?:int,icons:array<string,array{width?:int,height?:int,body:int}>,not_found:string[]} */
            $result = json_decode($response, true);

            if ($result === null) {
                $this->writer()
                    ->error('Oops… Something wrong happened when decoding JSON string!', true);
                break;
            }

            foreach ($result['icons'] as $iconName => $data) {
                $width = $data['width'] ?? $result['width'] ?? 16;
                $height = $data['height'] ?? $result['height'] ?? 16;
                $svg = sprintf(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 %s %s">%s</svg>',
                    $width,
                    $height,
                    $data['body']
                );

                $iconSet->setIconFound($iconName, $svg);
            }

            // icon set was found
            $iconSet->setFound();

            break;
        }
    }

    private function extractIconsFromPHPFile(string $filePath): void
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();

        $fileContents = file_get_contents($filePath);

        if (! $fileContents) {
            $this->writer()
                ->error(sprintf('No such php file %s', $filePath), true);
            return;
        }

        try {
            $ast = $parser->parse($fileContents);
        } catch (Error $error) {
            $this->writer()
                ->error(sprintf('Parse error: %s', $error->getMessage()), true);
            return;
        }

        if ($ast === null) {
            return;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new IconsFunctionsVisitor($filePath, $this->iconData, $this->config));
        $traverser->addVisitor(new IconsAnnotationsVisitor($filePath, $this->iconData, $this->config));

        $traverser->traverse($ast);
    }

    /**
     * @return string[]
     */
    private function getPHPFilesToScan(): array
    {
        $filesToScan = [];
        foreach ($this->config->getPaths() as $path) {
            if (is_dir($path)) {
                $discoveredFiles = glob_recursive($path . '/*');

                if (! $discoveredFiles) {
                    // no files discovered in $path
                    $this->writer()
                        ->error(sprintf('Something happened when trying to discover files in %s', $path), true);

                    return [];
                }

                $filesToScan = [...$filesToScan, ...$discoveredFiles];
            } elseif (is_file($path)) {
                $filesToScan = [...$filesToScan, $path];
            }
        }

        // filter out non php files and duplicates
        return array_unique(array_filter($filesToScan, fn ($path) => str_ends_with($path, '.php')));
    }

    private function writeIcons(): bool
    {
        $iconsClassTemplate = file_get_contents(__DIR__ . '/../Templates/IconsClass.template.php');

        if (! $iconsClassTemplate) {
            throw new Exception('Failed to get Icons template.');
        }

        $iconsClass = str_replace('[/** ICONS_DATA **/]', varexport($this->iconData->getNew()), $iconsClassTemplate);

        return (bool) file_put_contents(dirname(__DIR__, 2) . '/Icons.php', $iconsClass);
    }

    private function renderErrors(): void
    {
        foreach ($this->iconData->getErrors() as $key => $errors) {
            foreach ($errors as $error) {
                $this->writer()
                    ->eol(2);
                $this->writer()
                    ->boldRed(sprintf(' [!] %s', $error->getTitle()), true);
                foreach ($error->getLocations() as $key => $location) {
                    $prefix = ' ├──';
                    if ($key === array_key_last($error->getLocations())) {
                        $prefix = ' └──';
                    }

                    $this->writer()
                        ->bold(sprintf('%s %s', $prefix, $location), true);
                }
            }
        }
    }

    private function renderDiff(): void
    {
        $this->writer()
            ->eol(2);
        if ($this->iconData->getStatistics()['added'] === 0 && $this->iconData->getStatistics()['removed'] === 0) {
            $this->writer()
                ->comment('// no change!', true);
        }

        foreach ($this->iconData->getAdded() as $iconKey) {
            $this->writer()
                ->green(sprintf('+ %s', $iconKey), true);
        }

        foreach ($this->iconData->getRemoved() as $iconKey) {
            $this->writer()
                ->red(sprintf('- %s', $iconKey), true);
        }
    }

    private function renderSummary(): void
    {
        $summary = '';
        foreach ($this->iconData->getStatistics() as $key => $value) {
            $error = 0;
            if (is_array($value)) {
                $error = $value[1];
                $value = $value[0];
            }

            $valueText = $value > 0 ? sprintf('<boldCyan>%s</end>', $value) : sprintf('<bold>%s</end>', $value);
            $errorText = $error > 0 ? sprintf(' <red>[! %s]</end>', $error) : '';

            if ($key !== array_key_first($this->iconData->getStatistics())) {
                $summary .= ', ';
            }
            $summary .= sprintf('%s %s%s', $key, $valueText, $errorText);
        }

        $this->writer()
            ->eol(2);
        $this->writer()
            ->colors(sprintf('<bold>✨ All done!</end>%s<eol>', ($this->dryRun ? ' <yellow>[dry-run]</end>' : '')));
        $this->writer()
            ->colors($summary . '<eol>');
    }
}
