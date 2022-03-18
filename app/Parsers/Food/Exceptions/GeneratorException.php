<?php
namespace App\Parsers\Food\Exceptions;

use App\Parsers\Food\Ast\Contracts\Node;
use App\Parsers\Food\Token;
use Exception;

class GeneratorException extends Exception {

    public function __construct(Node $node) {
        parent::__construct(
            sprintf(
                'GeneratorError: Unexpected node "%s"',
                get_class($node)
            )
        );
    }

}
