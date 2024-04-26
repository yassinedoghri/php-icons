<?php

declare(strict_types=1);

namespace PHPIcons\Console\Visitors;

use PHPIcons\Config\PHPIconsConfig;
use PHPIcons\Console\Icon;
use PHPIcons\Console\IconData;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
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

            foreach ($matches['iconKey'] as $iconKeyMatch) {
                $startFilePosition = $commentNode->getStartFilePos() + $iconKeyMatch[1];
                $line = substr_count(substr($commentNode->getText(), 0, $iconKeyMatch[1]), PHP_EOL);
                $this->iconData->addIcon(
                    new Icon(
                        $this->filePath,
                        new String_(
                            $iconKeyMatch[0],
                            [
                                'startLine'    => $commentNode->getStartLine() + $line,
                                'endLine'      => $commentNode->getStartLine() + $line,
                                'startFilePos' => $startFilePosition,
                                'endFilePos'   => $startFilePosition + strlen($iconKeyMatch[0]),
                            ]
                        ),
                        $this->config->getDefaultPrefix()
                    )
                );
            }
        }

        return null;
    }
}
