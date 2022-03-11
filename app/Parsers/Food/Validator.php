<?php
namespace App\Parsers\Food;

use DateTime;

class Validator {

    public function isDate(string $date): bool {
        return DateTime::createFromFormat("Y-m-d", $date)->format("Y-m-d") === $date;
    }

    public function isTime(string $time): bool {
        return DateTime::createFromFormat("H:i", $time)->format("H:i") === $time;
    }

}
