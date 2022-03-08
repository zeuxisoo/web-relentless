<?php
namespace App\Parsers\Food;

class TokenKind {

    public const Double  = "double";
    public const Integer = "integer";

    public const Date = "date";
    public const Time = "time";
    public const Tag  = "tag";

    public const Sharp = "sharp";
    public const At    = "at";

    public const EOF = "end_of_line";

}
