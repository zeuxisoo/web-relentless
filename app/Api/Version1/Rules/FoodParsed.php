<?php
namespace App\Api\Version1\Rules;

use App\Parsers\Food\Exceptions\GeneratorException;
use App\Parsers\Food\Exceptions\LexerException;
use App\Parsers\Food\Exceptions\ParserException;
use App\Parsers\Food\Helper as FoodParserHelper;
use Illuminate\Contracts\Validation\Rule;

class FoodParsed implements Rule {

    protected string $message;

    public function passes($attribute, $value): bool {
        try {
            FoodParserHelper::compile($value);

            return true;
        }catch(LexerException|ParserException|GeneratorException $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): string {
        return $this->message;
    }

}
