<?php
namespace App\Parsers\Food\Traits\Patterns;

trait LiteralPattern {

    protected function isLiteral(string $char): bool {
        return preg_match('/^[\x{4E00}-\x{2B738}a-zA-Z_]$/u', $char);
    }

    protected function readString(string $char): string {
        $value = $char;

        while(true) {
            $currentChar = $this->lookChar();

            if (!preg_match('/^[\x{4E00}-\x{2B738}a-zA-Z0-9_]$/u', $currentChar)) {
                break;
            }

            $value .= $this->readChar();
        }

        return $value;
    }

    protected function readRemark(string $char): string {
        $value = $char;

        while(true) {
            $currentChar = $this->lookChar();

            $isDoubleNewline     = $this->isNewline($currentChar) && $this->isNewline($this->lookNextChar());
            $isRightCurlyBracket = $this->isRightCurlyBracket($currentChar);

            if ($isDoubleNewline || $isRightCurlyBracket) {
                break;
            }

            if (!preg_match('/^[\x{4E00}-\x{2B738}a-zA-Z0-9_\-!$%^&*()_+\-={}\[\]:",;\s]$/u', $currentChar)) {
                break;
            }

            $value .= $this->readChar();
        }

        return rtrim($value); // remove newline/space/tab at the end of string
    }

}
