<?php

declare(strict_types=1);

namespace PHPIcons\Console\Visitors;

use PHPIcons\Config\PHPIconsConfig;
use PHPIcons\Console\Icon;
use PHPIcons\Console\IconData;
use PHPIcons\Console\IconNode;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class IconsAnnotationsVisitor extends NodeVisitorAbstract
{
    /**
     * @var array<Doc|Comment>
     */
    public array $comments = [];

    public function __construct(
        private readonly string $filePath,
        private readonly IconData $iconData,
        private readonly PHPIconsConfig $config
    ) {
    }

    public function enterNode(Node $node)
    {
        foreach ($node->getComments() as $nodeComment) {
            if (! in_array($nodeComment, $this->comments, true)) {
                $this->comments = [...$this->comments, $nodeComment];
            }
        }

        return null;
    }

    public function afterTraverse(array $nodes)
    {
        foreach ($this->comments as $commentNode) {
            // parse comment to get icon keys
            $matched = preg_match_all(
                "/@icon\s*\(\s*([\'\"])(?<iconKey>[a-z0-9\-\:]+)\\1\s*\)/",
                $commentNode->getText(),
                $matches,
                PREG_OFFSET_CAPTURE
            );

            if (! $matched) {
                continue;
            }

            /**
             * @var array{0:string,1:int} $iconKeyMatch
             */
            foreach ($matches['iconKey'] as $iconKeyMatch) {
                $startFilePosition = $commentNode->getStartFilePos() + $iconKeyMatch[1];
                $line = substr_count(substr($commentNode->getText(), 0, $iconKeyMatch[1]), PHP_EOL);

                $icon = new Icon($iconKeyMatch[0], $this->config->getDefaultPrefix());
                $icon->addNode(
                    new IconNode($this->filePath, $commentNode->getStartLine() + $line, $startFilePosition)
                );

                $this->iconData->addIcon($icon);
            }
        }

        return null;
    }
}
