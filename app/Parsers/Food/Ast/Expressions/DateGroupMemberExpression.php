<?php
namespace App\Parsers\Food\Ast\Expressions;

use App\Parsers\Food\Ast\Contracts\Expression;

class DateGroupMemberExpression implements Expression {

    public function __construct(
        public TimeExpression $time,
        public TagsExpression $tags,
        public FoodsExpression $foods,
        public RemarkExpression $remark,
    ) { }

}
