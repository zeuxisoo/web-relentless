<?php
namespace App\Parsers\Food\Traits\Patterns;

trait DateTimePattern {

    protected function isDate(string $value): bool {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
    }

    protected function isTime(string $value): bool {
        return preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value);
    }

}
