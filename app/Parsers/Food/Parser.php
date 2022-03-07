<?php
namespace App\Parsers\Food;

class Parser {

    public function __construct(
        public Lexer $lexer
    ) {
        $this->lexer->lex();
     }

}
