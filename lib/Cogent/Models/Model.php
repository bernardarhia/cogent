<?php

namespace Cogent\Models;

use Cogent\DataTypes\DataTypes;
use Cogent\DB\Connector;
use Cogent\DB\Queries;
use Cogent\Helpers\Error;
use Cogent\Helpers\HelpersController;

class Model extends Queries
{
    /**
     * 
     * 
     * 
     */
    static function  find($data = [], $selectable = null, $callback = null)
    {
        self::reset();

        // get current class name
        $className = strtolower(get_called_class());
        self::$query = "SELECT ";

        if (is_null($selectable) || $selectable == "*" || empty(trim($selectable)) || !$selectable) {
            self::$query .= "* FROM $className";
        } else {
            self::$query .= implode(", ", explode(" ", $selectable)) . " FROM $className";
        }
        if (is_array($data) && $data != array_values($data)) {
            self::$query .= " WHERE ";
            $i = 0;

            foreach ($data as $key => $value) {
                if ($i > 0) {
                    self::$query .= " AND ";
                }
                static::$executeArray[$key] = $value;
                self::$query .= "`$key` = :$key";
                $i++;
            }
            self::$query .= ";";
        } else if (is_array($data) && count($data) == 0) {
            self::$query .= ";";
        }
        try {
            $stmt = self::$connection->prepare(self::$query);
            $stmt->execute(static::$executeArray);
            self::$result = $stmt->fetchAll(Connector::FETCH_OBJ);
            self::$executed = true;
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::FIND_ERROR);
        }

        if (is_callable($callback)) {
            $callback(static::$result, static::$error);
        }

        return (object)self::$result;
    }

    static function  findOne($data, $selectable = null, $callback = null)
    {
        self::reset();

        if (empty($data) || count($data) == 0) throw new \Exception("Provide a key value pair to search with");
        // get current class name
        $className = strtolower(get_called_class());
        self::$query = "SELECT ";

        if (is_null($selectable) || $selectable == "*" || empty(trim($selectable)) || !$selectable) {
            self::$query .= "* FROM $className";
        } else {
            self::$query .= implode(", ", explode(" ", $selectable)) . " FROM $className";
        }
        if (is_array($data) && $data != array_values($data)) {
            self::$query .= " WHERE ";
            $i = 0;

            foreach ($data as $key => $value) {
                if ($i > 0) {
                    self::$query .= " AND ";
                }
                static::$executeArray[$key] = $value;
                self::$query .= "`$key` = :$key";
                $i++;
            }
            self::$query .= ";";
        } else if (is_array($data) && count($data) == 0) {
            self::$query .= ";";
        }
        try {
            $stmt = self::$connection->prepare(self::$query);
            $stmt->execute(static::$executeArray);
            self::$result = $stmt->fetch(Connector::FETCH_OBJ);
            self::$executed = true;
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::FIND_ERROR);
        }

        if (is_callable($callback)) {
            $callback(static::$result, static::$error);
        }

        return (object)self::$result;
    }

    static function delete($data, $callback = null)
    {
        self::reset();

        // get current class name
        $className = strtolower(get_called_class());
        self::$query = "DELETE FROM " . $className;

        if (is_array($data) && $data != array_values($data)) {
            self::$query .= " WHERE ";
            $i = 0;

            foreach ($data as $key => $value) {
                if ($i > 0) {
                    self::$query .= " AND ";
                }
                static::$executeArray[$key] = $value;
                self::$query .= "`$key` = :$key";
                $i++;
            }
            self::$query .= ";";
        } else if (is_array($data) && count($data) == 0) {
            self::$query .= ";";
        }
        try {
            $stmt = self::$connection->prepare(self::$query);

            self::$executed = $stmt->execute(static::$executeArray);;
            self::$result = [
                "executed" => self::$executed,
                "affectedRows" => $stmt->rowCount(),
            ];
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::FIND_ERROR);
        }

        if (is_callable($callback)) {
            $callback(static::$result, static::$error);
        }
        return (object)self::$result;
    }
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

    /**
     * 
     * 
     * 
     * 
     * 
     */
    static function update($data, $updatable, $callback)
    {
        self::reset();
        $className = strtolower(get_called_class());
        self::$query = "UPDATE " . $className . " SET";
        if (!is_null($data) && is_array($data) && array_values($data) != $data) {
            foreach ($data as $key => $value) {
                self::$query .= " $key = :$key,";
                self::$executeArray[":" . $key] = $value;
            }
        }
        if (!is_null($updatable) && is_array($updatable)) {
            self::$query = substr(self::$query, 0, -1) . " WHERE ";

            // Checks if updatable is an array with three items [column operator value]
            foreach ($updatable as $key => $value) {
                if (is_array($value)) {
                    self::$executeArray[":" . $value[0]] = $value[2];
                    self::$query .= $value[0] . " " . $value[1] . " :" . $value[0] . " AND ";
                } else {
                    self::$executeArray[":" . $key] = $value;
                    self::$query .= "$key = :$key AND ";
                }
            }
            self::$query =  substr(self::$query, 0, -4);
        }

        try {
            $stmt = self::$connection->prepare(self::$query);
            self::$executed = $stmt->execute(self::$executeArray);
            self::$result = [
                "executed" => self::$executed,
                "affectedRows" => $stmt->rowCount(),
            ];
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::UPDATE_ERROR);
        }

        if (is_callable($callback)) {
            $callback(self::$result, (object)self::$error);
        }

        return (object) self::$result;
    }
}