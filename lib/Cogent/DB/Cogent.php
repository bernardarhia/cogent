<?php

namespace Cogent\DB;

use Cogent\Interfaces\SchemaInterface;
use Cogent\Models\Model;

class Cogent extends Model implements SchemaInterface
{
    static function model(string $class, object $data): object
    {
        self::$data = $data->data;
        self::$className = $class;
        // $schema = new Schema;
        // create new class using the model name and extend the base class
        $className = ucfirst($class);
        $class = "class $className extends Cogent\DB\Cogent{}";
        eval($class);
        return new $className();
    }
}