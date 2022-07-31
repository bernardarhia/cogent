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

        $createdClass = "class $className extends Cogent\DB\Cogent{
            public function __construct(\$dataArr=[]){
                foreach (\$dataArr as \$key => \$value) {
                        static::\$dataArr[\$key] = \$value;
                        \$this->\$key = \$value;
                    }
            }
         }";
        eval($createdClass);

        $NewClass = new $className;
        return $NewClass;
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


    static function raw($query, $fields = null,  string $queryType = null, $fetch_type = 'all')
    {
        try {
            $stmt = self::$connection->prepare($query);
            $stmt->execute($fields);

            if (in_array(strtolower($queryType), ['delete', 'insert', 'update', 'alter', 'create'])) {
                return [
                    "affectedRows" => $stmt->rowCount(),
                    "metadata" => strtolower($queryType) == 'insert' ? self::$connection->lastInsertId() : null
                ];
            }
            return [
                "status" => $stmt->rowCount() > 0 ? 'true' : 'false',
                "metadata" => is_null($fetch_type) || $fetch_type == 'all' ? $stmt->fetchAll(Connector::FETCH_OBJ) : $stmt->fetch(Connector::FETCH_OBJ)
            ];
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::TRUNCATE_ERROR);
        }
    }

    static function fn($fn, $col, $alias = null)
    {
        return "$fn($col)" . (is_null($alias) ? "" : " AS $alias");
    }
}