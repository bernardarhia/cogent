<?php

namespace Cogent\Operators;

class Op
{
    const EQ = "=";
    const NEQ = "<>";
    const GT = ">";
    const GTE = ">=";
    const LT = "<";
    const LTE = "<=";

    static function GT($number)
    {
        return self::GT . " " . $number;
    }
    static function EQ($number)
    {
        return self::EQ . " " . $number;
    }
    static function NEQ($number)
    {
        return self::NEQ . " " . $number;
    }
    static function LT($number)
    {
        return self::LT . " " . $number;
    }
    static function LTE($number)
    {
        return self::LTE . " " . $number;
    }
    static function GTE($number)
    {
        return self::GTE . " " . $number;
    }

    static function IN(...$args)
    {
        return "IN ('" . implode("', '", $args) . "')";
    }
    static function LIKE($string)
    {
        return "LIKE '$string'";
    }

    static function NOT_LIKE($string)
    {
        return "NOT LIKE '$string'";
    }
    static function NOT_IN($array)
    {
        return "NOT IN (" . implode(", ", $array) . ")";
    }
    static function NULL()
    {
        return "IS NULL";
    }
    static function NOT_NULL()
    {
        return "IS NOT NULL";
    }
    static function BETWEEN($arg1, $arg2)
    {
        $btw1 =   "btw" . random_int(0, 2000);
        $btw2 =   "btw" . random_int(0, 2000);

        return [
            "sql" => "BETWEEN :" . $btw1 . " AND :" . $btw2,
            "params" => [
                $btw1 => $arg1,
                $btw2 => $arg2
            ]
        ];
    }
    static function NOT_BETWEEN($arg1, $arg2)
    {
        $btw1 =   "btw" . random_int(0, 2000);
        $btw2 =   "btw" . random_int(0, 2000);

        return [
            "sql" => "NOT BETWEEN :" . $btw1 . " AND :" . $btw2,
            "params" => [
                $btw1 = $arg1,
                $btw2 = $arg2
            ]
        ];
    }
}