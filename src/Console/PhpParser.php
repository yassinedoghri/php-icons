<?php

declare(strict_types=1);

namespace PHPIcons\Console;

use PHPIcons\Config\PHPIconsConfig;
use PhpToken;

class PhpParser
{
    public function __construct(
        private readonly string $code,
        private readonly string $filePath,
        private readonly IconData $iconData,
        private readonly PHPIconsConfig $config
    ) {
    }

    public function parse(): void
    {
        $tokens = PhpToken::tokenize($this->code);
        $tokensCount = count($tokens);

        for ($i = 0; $i < $tokensCount; $i++) {
            $currentToken = $tokens[$i];
            if ($currentToken->is(T_STRING) && in_array(
                $currentToken->text,
                $this->config->getIdentifiers(),
                true
            )) {
                for ($j = 2; $j < 4; $j++) {
                    if (($nextToken = $tokens[$i + $j])->is(T_CONSTANT_ENCAPSED_STRING)) {
                        $this->handleCallToken($nextToken);

                        break;
                    }
                }
            } elseif ($currentToken->is(T_COMMENT) || $currentToken->is(T_DOC_COMMENT)) {
                $this->handleCommentToken($currentToken);
            }
        }
    }

    private function handleCallToken(PhpToken $token): void
    {
        $icon = new Icon(trim($token->text, '\'"'), $this->config->getDefaultPrefix());

        $icon->addNode(
            new IconNode(
                $this->filePath,
                $token->line,
                $token->pos + 1 // adding +1 to skip first quotes and be consistent with comments token handling
            )
        );

        $this->iconData->addIcon($icon);
    }

    private function handleCommentToken(PhpToken $commentToken): void
    {
        $matched = preg_match_all(
            "/@icon\s*\(\s*([\'\"])(?<iconKey>[a-z0-9\-\:]+)\\1\s*\)/",
            $commentToken->text,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if (! $matched) {
            return;
        }

        /**
         * @var array{non-empty-string,int<-1, max>} $iconKeyMatch
         */
        foreach ($matches['iconKey'] as $iconKeyMatch) {
            $startFilePosition = $commentToken->pos + $iconKeyMatch[1];
            $line = substr_count(substr($commentToken->text, 0, $iconKeyMatch[1]), PHP_EOL);

            $icon = new Icon($iconKeyMatch[0], $this->config->getDefaultPrefix());
            $icon->addNode(new IconNode($this->filePath, $commentToken->line + $line, $startFilePosition));

            $this->iconData->addIcon($icon);
        }
    }
}
