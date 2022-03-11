<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\RemarkExpression;
use App\Parsers\Food\TokenKind;

trait RemarkParser {

    protected function parseRemarkExpression(): RemarkExpression {
        $remarkValue = "";
        $lookToken   = $this->lookToken();

        if ($lookToken->kind === TokenKind::GreaterThan) {
            $this->readToken(); // eat greater than first

            $remarkToken = $this->readToken();

            if ($remarkToken->kind === TokenKind::Remark) {
                $remarkValue =$remarkToken->value;
            }
        }

        return new RemarkExpression($remarkValue);
    }

}
