<?php
namespace App\Parsers\Food\Traits\Patterns;

trait SymbolPattern {

    protected function isSlash(string $char): bool {
        return $char === "/";
    }

    protected function isSharp(string $char): bool {
        return $char === "#";
    }

    protected function isAt(string $char): bool {
        return $char === "@";
    }

    protected function isLeftCurlyBracket(string $char): bool {
        return $char === "{";
    }

}
