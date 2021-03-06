<?php
namespace App\Parsers\Food;

class TokenKind {

    public const Double  = "double";
    public const Integer = "integer";
    public const String  = "string";

    public const Date   = "date";
    public const Time   = "time";
    public const Tag    = "tag";
    public const Remark = "remark";

    public const Sharp       = "sharp";
    public const At          = "at";
    public const GreaterThan = "greater_than";

    public const LeftCurlyBracket  = "left_curly_bracket";
    public const RightCurlyBracket = "right_curly_bracket";

    public const EOF = "end_of_file";

}
