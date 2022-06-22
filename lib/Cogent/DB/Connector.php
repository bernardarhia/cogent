<?php

namespace Cogent\DB;

use PDO as DBConnector;


class Connector extends DBConnector
{
    private $connection;
    public function __construct(string $engine = null, string $dbName = null, string $dbHost = null, string $dbUser = null, string $dbPass = null)
    {

        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->engine = $engine;
        try {
            //code...
            if ($this->dbName == null) {
                parent::__construct("$this->engine:host=$this->dbHost", $this->dbUser, $this->dbPass);
                $this->connection = new DBConnector("$this->engine:host=$this->dbHost", $this->dbUser, $this->dbPass);
            } else {
                parent::__construct("$this->engine:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
                $this->connection = new DBConnector("$this->engine:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            }
            return $this->connection;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}