<?php

declare(strict_types=1);

namespace PHPIcons\Console\Visitors;

use PHPIcons\Config\PHPIconsConfig;
use PHPIcons\Console\Icon;
use PHPIcons\Console\IconData;
use PHPIcons\Console\IconNode;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class IconsFunctionsVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly string $filePath,
        private readonly IconData $iconData,
        private readonly PHPIconsConfig $config
    ) {
    }

    public function enterNode(Node $node)
    {
        if (
            ($node instanceof FuncCall || $node instanceof MethodCall)
            && ($node->name instanceof Name || $node->name instanceof Identifier)
            && in_array($node->name->toString(), $this->config->getIdentifiers(), true)
            && $node->getArgs() !== [] && $node->getArgs()[0]->value instanceof String_
        ) {
            /** @var String_ $strNode */
            $strNode = $node->getArgs()[0]
->value;

            $icon = new Icon($strNode->value, $this->config->getDefaultPrefix());

            // add +1 to StartFilePos to set the column number just after the string quote
            $icon->addNode(new IconNode($this->filePath, $strNode->getLine(), $strNode->getStartFilePos() + 1));

            $this->iconData->addIcon($icon);
        }

        return null;
    }
}
