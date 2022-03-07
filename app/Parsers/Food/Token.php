<?php
namespace App\Parsers\Food;

class Token {

    public function __construct(
        public string $kind,
        public string $value,
        public int $line,
        public int $column,
    ) { }

}
