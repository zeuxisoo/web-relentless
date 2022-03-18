<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\TimeExpression;

trait TimeProducer {

    protected function produceTimeExpression(TimeExpression $node): string {
        return $node->value;
    }

}
