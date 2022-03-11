<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\FoodExpression;
use App\Parsers\Food\Token;
use App\Parsers\Food\TokenKind;

trait FoodParser {

    protected function parseFoodExpression(): FoodExpression {
        $name = $this->parseFoodNameExpression()->value;

        $this->skipFoodAtExpression();

        $quantity = $this->parseFoodQuantityExpression()->value;
        $unit     = $this->parseFoodUnitExpression()->value;

        return new FoodExpression($name, $quantity, $unit);
    }

    protected function parseFoodNameExpression(): Token {
        $lookToken = $this->lookToken();

        if ($lookToken->kind !== TokenKind::String) {
            $this->stopParser("Invalid food name after datetime record", $lookToken);
        }

        return $this->readToken();
    }

    protected function parseFoodQuantityExpression(): Token {
        $lookToken = $this->lookToken();

        if (!in_array($lookToken->kind, [TokenKind::Integer, TokenKind::Double])) {
            $this->stopParser("Invalid food quantity after @ symbol", $lookToken);
        }

        return $this->readToken();
    }

    protected function parseFoodUnitExpression(): Token {
        $lookToken = $this->lookToken();

        if ($lookToken->kind !== TokenKind::String) {
            $this->stopParser("Invalid food unit after quantity", $lookToken);
        }

        return $this->readToken();
    }

    protected function skipFoodAtExpression(): void {
        $currentToken = $this->readToken();

        if ($currentToken->kind !== TokenKind::At) {
            $this->stopParser("Invalid @ symbol after food name", $currentToken);
        }
    }

}
