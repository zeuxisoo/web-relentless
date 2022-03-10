<?php
namespace App\Parsers\Food\Ast;

use App\Parsers\Food\Ast\Contracts\Node;

class Program implements Node {

    public function __construct(
        public array $statements
    ) { }

}
