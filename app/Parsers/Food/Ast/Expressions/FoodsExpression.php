<?php
namespace App\Parsers\Food\Ast\Expressions;

use App\Parsers\Food\Ast\Contracts\Expression;

class FoodsExpression implements Expression {

    public function __construct(
        public array $values,
    ) { }

}
