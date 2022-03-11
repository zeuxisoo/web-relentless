<?php
namespace App\Parsers\Food\Ast\Statements;

use App\Parsers\Food\Ast\Contracts\DateStatement;
use App\Parsers\Food\Ast\Expressions\DateGroupMembersExpression;

class DateGroupStatement implements DateStatement {

    public function __construct(
        public string $value,
        public DateGroupMembersExpression $members,
    ) { }

}
