<?php
namespace App\Parsers\Food;

class Helper {

    public static function compile(string $content): array {
        $lexer     = new Lexer($content);
        $parser    = new Parser($lexer);
        $traverser = new Traverser($parser);
        $generator = new Generator($traverser);

        return $generator->generate();
    }

}
