<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Traits\Patterns\CommonPattern;
use App\Parsers\Food\Traits\Patterns\SymbolPattern;

class Lexer {

    use CommonPattern, SymbolPattern;

    protected int $contentLength;
    protected int $currentPosition;
    protected int $currentLine;
    protected int $currentColumn;

    public function __construct(
        public string $content
    ) {
        $this->contentLength   = mb_strlen($content);
        $this->currentPosition = 0;
        $this->currentLine     = 1;
        $this->currentColumn   = 0;
    }

    public function lex(): array {
        $tokens      = [];
        $currentChar = "";

        while($this->currentPosition < $this->contentLength) {
            $this->skipNewline();
            $this->skipWhitespace();

            $currentChar = $this->readChar();

            if ($this->isSlash($currentChar) && $this->isSlash($this->lookChar())) {
                $this->skipSingleComment();
                continue;
            }

            if ($this->isEndOfLine($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::EOF, $currentChar);
                continue;
            }

            // TODO: convert to tokens
        }

        return $tokens;
    }

    public function readChar(): string {
        $currentChar = mb_substr($this->content, $this->currentPosition, 1);

        if ($this->isNewline($currentChar)) {
            $this->currentColumn = 0;
            $this->currentLine++;
        }else{
            $this->currentColumn++;
        }

        $this->currentPosition++;

        return $currentChar;
    }

    public function lookChar(): string {
        $currentChar = mb_substr($this->content, $this->currentPosition, 1);

        return $currentChar;
    }

    public function skipNewline(): void {
        $currentChar = $this->lookChar();

        while($this->isNewline($currentChar)) {
            $this->readChar();

            $currentChar = $this->lookChar();
        }
    }

    public function skipWhitespace(): void {
        $currentChar = $this->lookChar();

        while($this->isWhitespace($currentChar)) {
            $this->readChar();

            $currentChar = $this->lookChar();
        }
    }

    public function skipSingleComment(): void {
        $this->readChar(); // eat second slash

        while(!$this->isNewline($this->lookChar())) {
            $this->readChar();
        }
    }

    public function addToken(string $kind, string $char): Token {
        return new Token(
            kind  : $kind,
            value : $char,
            line  : $this->currentLine,
            column: $this->currentColumn,
        );
    }

}
