<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Contracts\DateStatement;
use App\Parsers\Food\Ast\Expressions\DateGroupMemberExpression;
use App\Parsers\Food\Ast\Expressions\DateGroupMembersExpression;
use App\Parsers\Food\Ast\Statements\DateGroupStatement;
use App\Parsers\Food\Ast\Statements\DateSingleStatement;
use App\Parsers\Food\Token;
use App\Parsers\Food\TokenKind;

trait DateParser {

    use TimeParser, TagsParser, FoodsParser, RemarkParser;

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
            foods : $this->parseFoodsExpression(),
            remark: $this->parseRemarkExpression(),
        );
    }

    protected function parseDateGroupStatement(Token $token): DateGroupStatement {
        return new DateGroupStatement(
            value  : $token->value,
            members: new DateGroupMembersExpression($this->parseDateGroupMembersExpression())
        );
    }

    protected function parseDateGroupMembersExpression(): array {
        $rows = [];

        while(true) {
            $rows[] = new DateGroupMemberExpression(
                time  : $this->parseTimeExpression(),
                tags  : $this->parseTagsExpression(),
                foods : $this->parseFoodsExpression(),
                remark: $this->parseRemarkExpression(),
            );

            if ($this->lookToken()->kind === TokenKind::RightCurlyBracket) {
                $this->readToken(); // eat the right curly bracket first
                break;
            }
        }

        return $rows;
    }

}
