<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Contracts\Statement;
use App\Parsers\Food\Ast\Program;
use App\Parsers\Food\Ast\Statements\ExpressionStatement;
use App\Parsers\Food\Exceptions\ParserException;
use App\Parsers\Food\Traits\Parsers\DateParser;

class Parser {

    use DateParser;

    protected array $tokens = [];
    protected ?Validator $validator = null;

    public function __construct(
        public Lexer $lexer
    ) {
        $this->tokens    = $lexer->lex();
        $this->validator = new Validator();
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
                case TokenKind::Date:
                    $statements[] = $this->parseDateStatement($currentToken);
                    break;
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

    protected function lookToken(): ?Token {
        return reset($this->tokens);
    }

    protected function stopParser(string $message, Token $token): never {
        throw new ParserException($message, $token);
    }

}
