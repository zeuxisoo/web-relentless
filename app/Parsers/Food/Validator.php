<?php
namespace App\Parsers\Food;

use DateTime;

class Validator {

    public function isDate(string $date): bool {
        return DateTime::createFromFormat("Y-m-d", $date)->format("Y-m-d") === $date;
    }

}
