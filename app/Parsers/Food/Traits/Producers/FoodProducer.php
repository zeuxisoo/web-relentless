<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\FoodExpression;

trait FoodProducer {

    protected function produceFoodExpression(FoodExpression $node): array {
        return [
            'name'     => $node->name,
            'quantity' => $node->quantity,
            'unit'     => $node->unit,
        ];
    }

}
