<?php
namespace App\Parsers\Food\Traits\Parsers;

use App\Parsers\Food\Ast\Expressions\TimeExpression;

trait TimeParser {

    protected function parseTimeExpression(): TimeExpression {
        $token = $this->readToken();

        if (!$this->validator->isTime($token->value)) {
            $this->stopParser("Invalid time string", $token);
        }

        return new TimeExpression($token->value);
    }

}
