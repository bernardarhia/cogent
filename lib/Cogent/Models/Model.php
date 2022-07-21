<?php

namespace Cogent\Models;

use Cogent\DB\Queries;
use Cogent\Models\Keywords;
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
            self::$query = KEYWORDS::SELECT . " ";
            // check if it's an array
            if (is_null($options) || empty(($options)) || count($options) == 0) {
                self::$query .= "*";
            }

            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    if (is_string($key)) self::$query .= "$key as `$value`, ";
                    else self::$query .= "$value, ";
                }
                self::$query = substr(self::$query, 0, strlen(self::$query) - 2);
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
        return new static;
    }

    static function update($rules)
    {
        self::reset();
        if (!is_array($rules) || array_values($rules) == $rules) throw new \Exception("Update error");

        self::$query = KEYWORDS::UPDATE . " " . strtolower(self::$className) . " " . KEYWORDS::SET . " ";
        // self::$query .= implode(",", ($rules));
        foreach ($rules as $key => $value) {
            self::$query .= "`$key` = :$key, ";
            self::$executeArray[":$key"] = $value;
        }
        self::$query = substr(self::$query, 0, strlen(self::$query) - 2);
        return new static;
    }

    function or($data = [])
    {
        // Remove or and from the end of the query
        self::$query = trim(self::removeAndOr(self::$query));
        self::$query .= " OR ";
        return new static;
    }

    static function where(...$args)
    {
        if (empty($args) || count($args) == 0) throw new \Exception("No data passed in");
        // Remove or and from the end of the query
        $split = explode(" ", trim(self::$query));
        $lastWord = trim($split[count($split) - 1]);

        // return new static;

        // Check if string has the key word AND or OR then remove them
        if (!in_array(strtolower($lastWord), ["or", "and"])) {
            self::$query .= " " . KEYWORDS::WHERE . " ";
        }

        // check if the args passed was one and if it was an array
        if (count($args) == 1 && is_array($args[0])) {
            $args = $args[0];
            /**
             * Loop through the array and create key value pair for each item
             * but if the value is an array then loop through it and map it to the key of the array
             * 
             */
            foreach ($args as $key => $value) {
                if (is_string($value)) {
                    self::$query .= ("`$key` $value AND ");
                }
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        // IF the items passed are normal array items
                        if (is_numeric($k)) self::$query .= ("$key $v AND ");

                        /* pass in the sql of the value
                        this was done because keywords like between are written as named parameters 
                        **/
                        if (is_string($k)) {
                            self::$query .= $key . " " . $value['sql'];
                            break;
                        }
                    }
                }
            }
        }

        /**
         * 
      Arguments are two example (id, 1) or  (id, [1,2,3])
         */
        else if (count($args) == 2) {
            if (!is_array($args[1])) {
                self::$query .= " (`$args[0]` = '$args[1]') AND";
            } else if (is_array($args[1])) {
                foreach ($args[1] as $key => $value) {
                    self::$query .= " (`$args[0]` = '$value') AND";
                }
            }
        }
        /**
         * 
      Arguments are two example (id,>, 1)
         */
        else if (count($args) == 3) {
            self::$query .= " (" . $args[0] . " " . $args[1] . " " . $args[2] . ") AND";
        }
        return new static;
    }
    function whereLike($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                self::$query .= "`$key` LIKE :$key AND ";
            }
            self::$query = substr(self::$query, 0, strlen(self::$query) - 4);
        }
    }
    function whereNotLike($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                self::$query .= "`$key` NOT LIKE :$key AND ";
            }
            self::$query = substr(self::$query, 0, strlen(self::$query) - 4);
        }
    }

    function whereIn($field = null, $data)
    {
        self::$query .= "`$field` IN (" . implode(",", $data) . ")";
    }
    function whereNotIn($data = null)
    {
    }
    function whereBetween($data = null)
    {
    }
    function whereNotBetween($data = null)
    {
    }
    function whereNull($data = null)
    {
    }
    function whereNotNull($data = null)
    {
    }

    function limit($number)
    {
        self::$query .= " " . KEYWORDS::LIMIT . "  $number";
        return new static;
    }
    function groupBy($field)
    {
        self::$query = trim(self::removeAndOr(self::$query));
        self::$query .= " " . KEYWORDS::GROUP_BY . " $field";
        return new static;
    }
    function orderBy($field, $sortType = "ASC")
    {
        self::$query = trim(self::removeAndOr(self::$query));
        self::$query .= " " . KEYWORDS::ORDER_BY . " $field $sortType";
        return new static;
    }

    function get($callback = null)
    {

        echo (self::$query) . "\n";
    }

    static function removeAndOr($query)
    {
        $split = explode(" ", trim($query));
        $lastWord = ($split[count($split) - 1]);
        $lastWordCount  = strlen($lastWord);

        if (in_array(strtolower($lastWord), ["and", "or"])) {
            $query = substr($query, 0, -$lastWordCount - 1);
        }
        return $query;
    }

    static function sum($column, $alias = null)
    {
    }
    static function count($column, $alias = null)
    {
        self::$query .= "COUNT($column)";

        !is_null($alias) ? self::$query .= " as $alias" : "";
        return new static;
    }
}