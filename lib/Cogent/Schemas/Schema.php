<?php

namespace Cogent\Schemas;

class Schema
{
    public $data = null;
    static $enum = [];


    public function __construct($data = [])
    {
        $this->data = $data;
    }

    static function Enum(...$args)
    {
        self::$enum = [...$args];
        return "enum";
    }
}