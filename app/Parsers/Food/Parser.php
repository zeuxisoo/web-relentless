<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Contracts\Statement;
use App\Parsers\Food\Ast\Program;
use App\Parsers\Food\Ast\Statements\ExpressionStatement;

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
                default:
                    $statement = $this->parseExpressionStatement($currentToken);

                    if ($statement !== null) {
                        $statements[] = $statement;
                    }
                    break;
            }

            $currentToken = $this->readToken();
        }

        return $statements;
    }

    protected function parseExpressionStatement(Token $token): ?Statement {
        $expression = null;

        switch($token->kind) {
            case TokenKind::EOF:
                // Nothing todo
                break;
        }

        return $expression !== null ? new ExpressionStatement($expression) : $expression;
    }

    protected function readToken(): ?Token {
        return array_shift($this->tokens);
    }

}
