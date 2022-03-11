<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\TagsExpression;
use App\Parsers\Food\TokenKind;

trait TagsParser {

    use TagParser;

    protected function parseTagsExpression(): TagsExpression {
        $tagTokens = [];
        $lookToken = $this->lookToken();

        while(true) {
            if ($lookToken->kind !== TokenKind::Sharp) {
                break;
            }

            // Eat sharp first
            $this->readToken();

            // Parse tag to tag tokens
            $tagTokens[] = $this->parseTagExpression();

            // Loop next token
            $lookToken = $this->lookToken();
        }

        return new TagsExpression($tagTokens);
    }

}
