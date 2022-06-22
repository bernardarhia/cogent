<?php

namespace Cogent\Helpers;

class Error
{
    const CONNECTION_ERROR = 2233;
    const CONNECTION_STRING_ERROR = 2234;

    public static function createError($message = null, $code = null)
    {
        return (object) [
            "error" => "true",
            "code" => $code,
            "message" => $message
        ];
    }
}