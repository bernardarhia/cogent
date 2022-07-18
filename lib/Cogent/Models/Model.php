<?php

namespace Cogent\Models;

use Cogent\DB\Queries;
use Cogent\Models\Queries as ModelQUeries;
use Cogent\Helpers\Error;
use Cogent\Helpers\HelpersController;

class Model extends Queries
{
    /**
     */

    /**
     * 
     * 
     * 
     * 
     */

    private function createTable()
    {
        $data = HelpersController::formatArray(self::$data);

        // Create table schema from data
        $sql = "";
        foreach ($data as $key => $value) {
            $sql .= "`$key` $value,\n";
        }
        // static::$connection->prepare();
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql = "(\n" . $sql . "\n)";

        // Create table
        $sql = "create table if not exists `" . strtolower(self::$className) . "` " . $sql;
        try {
            $stmt = static::$connection->query($sql);
        } catch (\Throwable $th) {
            static::$error = Error::createError($th->getMessage(), Error::TABLE_CREATION_ERROR);
        }
    }

    /**
     * 
     * 
     * 
     * 
     */

    static public function save($callback = null)
    {
        self::reset();

        try {
            // Create the table if it doesn't exist
            // $class = new $calledClass;
            $class = new Model;
            $class->createTable();

            // Insert data
            $sql = "(`" . implode("`, `", array_keys(self::$dataArr)) . "`) values(:" . implode(",:", array_keys(self::$dataArr)) . ")";
            // self::$result = $sql;
            foreach (self::$dataArr as $key => $value) {
                self::$executeArray[":" . $key] = $value;
            }
            $sql = "insert into `" . strtolower(self::$className) . "` " . $sql;
            $stmt = self::$connection->prepare($sql);
            $stmt->execute(self::$executeArray);
            if ($stmt->rowCount() > 0) {
                self::$dataArr['id'] = self::$connection->lastInsertId();
                self::$result = (object)self::$dataArr;
            } else throw new \Exception("Unable to insert data in db");
        }
        // Catch error
        catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::INSERT_ERROR);
        }

        if (is_callable($callback)) {
            $callback(self::$result, (object)self::$error);
        }
        return (object) self::$result;
    }

    static function find($options = null, $callback = null)
    {
        self::reset();
        try {
            // Create the table if it doesn't exist
            // $class = new $calledClass;
            $class = new Model;
            $class->createTable();
            // Find data
            self::$query = ModelQUeries::SELECT . " ";
            // check if it's an array
            if (is_null($options) || empty(($options)) || count($options) == 0) {
                self::$query .= "*";
            }

            if (is_array($options)) {
            } else {
                self::$query .= $options;
            }
            // If data passed in is a string

            self::$query .= " from `" . strtolower(self::$className) . "`";
        }
        // Catch error
        catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::SELECT_ERROR);
        }
        if (is_callable($callback)) {
            $callback(self::$result, (object)self::$error);
        }
        return self::$query;
    }

    function or($data = [])
    {
    }
}