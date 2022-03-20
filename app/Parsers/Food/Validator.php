<?php
namespace App\Parsers\Food;

use DateTime;

class Validator {

    public function isDate(string $date): bool {
        $datetime = DateTime::createFromFormat("Y-m-d", $date);

        if ($datetime !== false) {
            return $datetime->format("Y-m-d") === $date;
        }

        return false;
    }

    public function isTime(string $time): bool {
        $datetime = DateTime::createFromFormat("H:i", $time);

        if ($datetime !== false) {
            return $datetime->format("H:i") === $time;
        }

        return false;
    }

}
