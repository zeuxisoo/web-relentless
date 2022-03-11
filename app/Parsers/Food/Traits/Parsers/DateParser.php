<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Contracts\DateStatement;
use App\Parsers\Food\Ast\Statements\DateGroupStatement;
use App\Parsers\Food\Ast\Statements\DateSingleStatement;
use App\Parsers\Food\Token;
use App\Parsers\Food\TokenKind;

trait DateParser {

    use TimeParser, TagsParser;

    protected function parseDateStatement(Token $token): DateStatement {
        if (!$this->validator->isDate($token->value)) {
            $this->stopParser("Invalid date string", $token);
        }

        $lookToken = $this->lookToken();

        if ($lookToken->kind === TokenKind::Time) {
            return $this->parseDateSingleStatement($token);
        }

        if ($lookToken->kind === TokenKind::LeftCurlyBracket) {
            $this->readToken(); // eat the left curly bracket first

            return $this->parseDateGroupStatement($token);
        }
    }

    protected function parseDateSingleStatement(Token $token): DateSingleStatement {
        return new DateSingleStatement(
            value : $token->value,
            time  : $this->parseTimeExpression(),
            tags  : $this->parseTagsExpression(),
        );
    }

    protected function parseDateGroupStatement(Token $token): DateGroupStatement {
        // TODO: Implement parseDateGroupStatement() method.
        return new DateGroupStatement();
    }

}