<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\TagExpression;
use App\Parsers\Food\TokenKind;

trait TagParser {

    protected function parseTagExpression(): TagExpression {
        $currentToken = $this->readToken();

        if ($currentToken->kind !== TokenKind::Tag) {
            $this->stopParser("Invalid tag value after sharp token", $currentToken);
        }

        return new TagExpression($currentToken->value);
    }

}
