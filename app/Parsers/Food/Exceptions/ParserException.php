<?php
namespace App\Parsers\Food\Exceptions;

use App\Parsers\Food\Token;
use Exception;

class ParserException extends Exception {

    public function __construct(string $message, Token $token) {
        parent::__construct(
            sprintf(
                "ParseError: %s, %s at line %d, column %s\n",
                $message,
                $token->value,
                $token->line,
                implode("...", [
                    // Trim left space length before calculate
                    ($token->column + 1) - mb_strlen($token->value),
                    $token->column,
                ]),
            )
        );
    }

}
