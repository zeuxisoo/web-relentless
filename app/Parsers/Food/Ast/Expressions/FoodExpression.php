<?php
namespace App\Parsers\Food\Ast\Expressions;

use App\Parsers\Food\Ast\Contracts\Expression;

class FoodExpression implements Expression {

    public function __construct(
        public string $name,
        public float $quantity,
        public string $unit,
    ) { }

}
