<?php
namespace App\Parsers\Food;

class Lexer {

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

            $currentChar = $this->readChar();

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

    protected function isNewline(string $char): bool {
        return preg_match('/[\r|\n|\r\n]/', $char);
    }

}
