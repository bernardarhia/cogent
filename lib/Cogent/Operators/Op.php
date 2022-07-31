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

    static function GT($column)
    {
        return  (object) [
            "operator" => self::GT,
            "value" => $column
        ];
    }
    static function EQ($column)
    {
        return  (object) [
            "operator" => self::EQ,
            "value" => $column
        ];
    }
    static function NEQ($column)
    {
        return  (object) [
            "operator" => self::NEQ,
            "value" => $column
        ];
    }
    static function LT($column)
    {
        return  (object) [
            "operator" => self::LT,
            "value" => $column
        ];
    }
    static function LTE($column)
    {
        return  (object) [
            "operator" => self::LTE,
            "value" => $column
        ];
    }
    static function GTE($column)
    {
        return  (object) [
            "operator" => self::GTE,
            "value" => $column
        ];
    }

    static function IN(...$args)
    {
        return (object) ["sql" => "IN ('" . implode("', '", $args) . "')"];
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
        $btw1 =   "__btw1";
        $btw2 =   "__btw2";

        return (object)[
            "sql" => "BETWEEN :" . $btw1 . " AND :" . $btw2,
            "params" => [
                $btw1 => $arg1,
                $btw2 => $arg2
            ]
        ];
    }
    // static function NOT_BETWEEN($arg1, $arg2)
    // {
    //     $btw1 =   "btw" . random_int(0, 2000);
    //     $btw2 =   "btw" . random_int(0, 2000);

    //     return [
    //         "sql" => "NOT BETWEEN :" . $btw1 . " AND :" . $btw2,
    //         "params" => [
    //             $btw1 = $arg1,
    //             $btw2 = $arg2
    //         ]
    //     ];
    // }
}