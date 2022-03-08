<?php
namespace App\Parsers\Food\Traits\Patterns;

trait TagPattern {

    protected function isTag(string $char): bool {
        return preg_match('/^[\x{4E00}-\x{9FFF}a-zA-Z0-9_\-]$/u', $char);
    }

    protected function readTag(string $char): string {
        $value = $char;

        while(true) {
            $currentChar = $this->lookChar();

            if (!preg_match('/^[\x{4E00}-\x{9FFF}a-zA-Z0-9_\-]$/u', $currentChar)) {
                break;
            }

            $value .= $this->readChar();
        }

        return $value;
    }

}
