<?php

namespace Cogent\Helpers;

class Error
{
    const CONNECTION_ERROR = 2233;
    const CONNECTION_STRING_ERROR = 2234;
    const TABLE_CREATION_ERROR = 2235;
    const TRUNCATE_ERROR = 2236;
    const DROP_ERROR = 2237;
    const INSERT_ERROR = 2238;
    const UPDATE_ERROR = 2239;
    const DELETE_ERROR = 2240;
    const SELECT_ERROR = 2241;
    const FIND_ERROR = 2242;

    public static function createError($message = null, $code = null)
    {
        return (object) [
            "error" => "true",
            "code" => $code,
            "message" => $message
        ];
    }
}