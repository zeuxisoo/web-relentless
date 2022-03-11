<?php
namespace App\Parsers\Food\Exceptions;

use Exception;

class LexerException extends Exception {

    public function __construct(string $currentChar, int $currentLine, int $currentColumn) {
        $codePoint = ord($currentChar);

        parent::__construct(
            sprintf(
                'LexerError: Unexpected character "%s" (code: %d) at line %d, column %d',
                $currentChar,
                $codePoint,
                $currentLine,
                $currentColumn
            )
        );
    }

}
