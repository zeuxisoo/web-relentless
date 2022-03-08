<?php
namespace App\Parsers\Food\Traits\Patterns;

trait SymbolPattern {

    protected function isSlash(string $char): bool {
        return $char === "/";
    }

}
