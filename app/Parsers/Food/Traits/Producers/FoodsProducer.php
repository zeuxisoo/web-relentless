<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\FoodsExpression;

trait FoodsProducer {

    protected function produceFoodsExpression(FoodsExpression $node): array {
        $foods = [];

        foreach($node->values as $food) {
            $foods[] = $this->produce($food);
        }

        return $foods;
    }

}
