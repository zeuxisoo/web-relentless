<?php
namespace App\Parsers\Food\Traits\Patterns;

trait CommonPattern {

    protected function isNewline(string $char): bool {
        return preg_match('/[\r|\n|\r\n]/', $char);
    }

    protected function isWhitespace(string $char): bool {
        return $char === " ";
    }

}
