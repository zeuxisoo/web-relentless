<?php
namespace App\Parsers\Food\Ast\Expressions;

use App\Parsers\Food\Ast\Contracts\Expression;

class TimeExpression implements Expression {

    public function __construct(
        public string $value,
    ) { }

}
