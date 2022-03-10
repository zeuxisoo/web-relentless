<?php
namespace App\Parsers\Food\Ast\Statements;

use App\Parsers\Food\Ast\Contracts\Expression;
use App\Parsers\Food\Ast\Contracts\Statement;

class ExpressionStatement implements Statement {

    public function __construct(
        public ?Expression $expression
    ) { }

}
