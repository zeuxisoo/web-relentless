<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\FoodsExpression;
use App\Parsers\Food\TokenKind;

trait FoodsParser {

    use FoodParser;

    protected function parseFoodsExpression(): FoodsExpression {
        $foodTokens = [];

        while(true) {
            $foodTokens[] = $this->parseFoodExpression();

            $lookToken = $this->lookToken();

            if ($lookToken->kind !== TokenKind::String) {
                break;
            }
        }

        return new FoodsExpression($foodTokens);
    }

}
