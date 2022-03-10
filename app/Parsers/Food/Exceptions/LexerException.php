<?php
namespace App\Parsers\Food\Exceptions;

use Exception;

class LexerException extends Exception {

    public function __construct(
        public string $currentChar,
        public int $currentLine,
        public int $currentColumn,
    ) {
        $codePoint = ord($currentChar);

        parent::__construct(
            sprintf(
                'Unexpected character "%s" (code: %d) at line %d, column %d',
                $currentChar,
                $codePoint,
                $currentLine,
                $currentColumn
            )
        );
    }

}
