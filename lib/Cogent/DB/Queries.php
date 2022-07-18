<?php

namespace Cogent\DB;

use Cogent\Helpers\Error;

class Queries
{
    protected static $data = null;
    protected static $className = null;
    protected static $dataArr = [];
    protected static $error = null;
    protected static $result = null;
    protected static $executed = null;
    protected static $executeArray = null;
    protected static $query = null;
    protected static $connection = null;
    protected static $fetchMode = [
        "ONE", "ALL"
    ];
    /**
     * 
     * @param string[]|null $data 
     * Finds to match in the table
     * 
     * @param string|null $selectable 
     * A list of strings specifying the fields to select in the table
     * Pass null if you want to select all fields
     * 
     * @param callback|null $callback 
     * A callback function which returns a
     *  response and an error if it has any from the query
     */


    /**
     * 
     * @param string|null $connectionString Format == dialect/host/user/database/password
     */
    static function connection($connectionString = null, $options = null, $callback = null)
    {
        $connectionString = trim($connectionString);

        try {
            if (is_null($connectionString) || empty($connectionString)) {
                throw new \Exception("No connection string found", 1);
            }

            $splittedStrings = explode("/", trim($connectionString));
            $filteredArray = array_filter($splittedStrings, function ($arr) {
                return $arr;
            });

            if (count($filteredArray) < 3) throw new \Exception("Connection string needs at least three values", 1);

            $dialect = $filteredArray[0] ?? null;
            $host = $filteredArray[1] ?? null;
            $user = $filteredArray[2] ?? null;
            $database = $filteredArray[3] ?? null;
            $password = $filteredArray[4] ?? null;
            self::$connection = new Connector($dialect, $database, $host, $user, $password, $options);

            if (self::$connection) return true;
            throw new \Exception("Unable to connect to database", 1);
        } catch (\Throwable $th) {
            self::$error = Error::createError($th->getMessage(), Error::CONNECTION_ERROR);
        }

        if (is_callable($callback)) {
            $callback(static::$error);
        } else {
            return self::$error;
        }
    }

    public function getQuery()
    {
        return self::$query;
    }

    protected static function reset()
    {
        // self::$data = null;
        self::$error = null;
        self::$result = null;
        self::$executed = null;
        self::$executeArray = null;
        self::$query = null;
    }
}