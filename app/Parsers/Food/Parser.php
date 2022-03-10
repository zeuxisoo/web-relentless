<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Program;

class Parser {

    protected array $tokens;

    public function __construct(
        public Lexer $lexer
    ) {
        $this->tokens = $lexer->lex();
    }

    public function parse(): Program {
        $statements = $this->parseStatements();
        $program    = new Program($statements);

        return $program;
    }

    protected function parseStatements(): array {
        $statements   = [];
        $currentToken = $this->readToken();

        while ($currentToken !== null) {

            switch($currentToken->kind) {
                // TODO: parse each token
                default:
                    break;
            }

            $currentToken = $this->readToken();
        }

        return $statements;
    }

    protected function readToken(): ?Token {
        return array_shift($this->tokens);
    }

}
