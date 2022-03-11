<?php
namespace App\Parsers\Food\Ast\Statements;

use App\Parsers\Food\Ast\Contracts\DateStatement;
use App\Parsers\Food\Ast\Expressions\TagsExpression;
use App\Parsers\Food\Ast\Expressions\TimeExpression;

class DateSingleStatement implements DateStatement {

    public function __construct(
        public string $value,
        public TimeExpression $time,
        public TagsExpression $tags,
    ) { }

}
