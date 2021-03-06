<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Statements\DateSingleStatement;

trait DateProducer {

    protected function produceDateSingleStatement(DateSingleStatement $node): array {
        return [
            'date'   => $node->value,
            'time'   => $this->produce($node->time),
            'tags'   => $this->produce($node->tags),
            'foods'  => $this->produce($node->foods),
            'remark' => $this->produce($node->remark),
        ];
    }

}
