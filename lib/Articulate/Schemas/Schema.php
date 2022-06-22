<?php

namespace Articulate\Schemas;

use Articulate\Interfaces\SchemaInterface;

class Schema  implements SchemaInterface
{
    static function model(string $class, array $data): object
    {
        // $schema = new Schema;
        // create new class using the model name and extend the base class
        $className = ucfirst($class);
        $class = "class $className extends Articulate\Models\Model{}";
        eval($class);
        return new $className();
    }
}