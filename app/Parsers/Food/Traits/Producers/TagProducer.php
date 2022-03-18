<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\TagExpression;

trait TagProducer {

    protected function produceTagExpression(TagExpression $node): string {
        return $node->value;
    }

}
