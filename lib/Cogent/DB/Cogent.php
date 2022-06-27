<?php

namespace Cogent\DB;

use Cogent\Interfaces\SchemaInterface;
use Cogent\Models\Model;
use Cogent\Helpers\Error;

class Cogent extends Model implements SchemaInterface
{
    static function model(string $class, object $data, $options = null): object
    {
        self::$data = $data->data;
        self::$className = $class;
        // $schema = new Schema;
        // create new class using the model name and extend the base class
        $className = ucfirst($class);
        $class = "class $className extends Cogent\DB\Cogent{
            public function __construct(\$dataArr=[]){
                foreach (\$dataArr as \$key => \$value) {
                        static::\$dataArr[\$key] = \$value;
                        \$this->\$key = \$value;
                    }
                    return \$this;
            }
         }";
        eval($class);
        return new $className;
    }
    static function empty($tableName)
    {
        // Truncate the table name
        $sql = "TRUNCATE TABLE `$tableName`";
        try {
            $stmt = self::$connection->prepare($sql);
            $stmt->execute();
            return (object)[
                "status" => true,
                "message" => "Table $tableName has been truncated"
            ];
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::TRUNCATE_ERROR);
        }
    }

    static function drop($tableName)
    {
        // Drop the table name
        $sql = "DROP TABLE IF EXISTS `$tableName`";
        try {
            $stmt = self::$connection->prepare($sql);
            $stmt->execute();
            return (object)[
                "status" => true,
                "message" => "Table $tableName has been dropped"
            ];
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::TRUNCATE_ERROR);
        }
    }
}