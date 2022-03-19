<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\RemarkExpression;

trait RemarkProducer {

    protected function produceRemarkExpression(RemarkExpression $node): string {
        return $node->value;
    }

}
