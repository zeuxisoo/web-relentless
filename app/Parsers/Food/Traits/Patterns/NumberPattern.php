<?php
namespace App\Parsers\Food\Traits\Patterns;

trait NumberPattern {

    protected function isDigit(string $char): bool {
        return preg_match('/^[0-9]$/', $char);
    }

    protected function isInteger(string $text): bool {
        return preg_match('/^[0-9]+$/', $text);
    }

    protected function isDouble(string $text): bool {
        return preg_match('/^[0-9\.]+$/', $text);
    }

}
