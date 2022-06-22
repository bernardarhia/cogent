<?php

namespace Cogent\Schemas;

use Cogent\Interfaces\DataTypesInterface;

class Schema implements DataTypesInterface
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