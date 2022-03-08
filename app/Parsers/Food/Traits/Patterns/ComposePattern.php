<?php
namespace App\Parsers\Food\Traits\Patterns;

trait ComposePattern {

    protected function readNumberOrDateOrTime(string $char): string {
        $value = $char;

        while(true) {
            $currentChar = $this->lookChar();

            if (!preg_match('/[0-9\.\-\:]+/', $currentChar)) {
                break;
            }

            $value .= $this->readChar();
        }

        return $value;
    }

}
