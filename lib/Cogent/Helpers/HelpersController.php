<?php

namespace Cogent\Helpers;


class HelpersController
{

    public function findAndReplace($key, $string, $replace)
    {
        if (strpos($key, $string) !== false) {
            return str_replace($key, $replace,  $string);
        }
        return $string;
    }
    static function turnString($from, $to)
    {
        return $to;
    }
}