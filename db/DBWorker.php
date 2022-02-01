<?php

class DBWorker {
    public static $DEF_DB_NAME = "dbname";
    public static $DEF_DB_HOST = "dbhost";
    public static $DEF_DB_USER = "dbuser";
    public static $DEF_DB_PASS = "dbpass";


    private static $INSTANCE = null;

    protected $dbName;
    protected $dbHost;
    protected $dbUser;
    protected $dbPass;

    protected $link;
    protected $result;

    private function __construct() {    }
    private function __clone()     {    }
    public function __wakeup()    {    }

    public static function getInstance($dbName, $dbHost, $dbUser, $dbPass)
    {
        if(is_null(self::$INSTANCE))
        {
            self::$INSTANCE = new DBWorker();
            self::$INSTANCE->dbName = $dbName;
            self::$INSTANCE->dbHost = $dbHost;
            self::$INSTANCE->dbUser = $dbUser;
            self::$INSTANCE->dbPass = $dbPass;
            self::$INSTANCE->openConnection();
        }
        return self::$INSTANCE;
    }

    public static function getDefInstance()
    {
        if(is_null(self::$INSTANCE))
        {
            self::$INSTANCE = new DBWorker();
            self::$INSTANCE->dbName = self::$DEF_DB_NAME;
            self::$INSTANCE->dbHost = self::$DEF_DB_HOST;
            self::$INSTANCE->dbUser = self::$DEF_DB_USER;
            self::$INSTANCE->dbPass = self::$DEF_DB_PASS;
            self::$INSTANCE->openConnection();
        }
        return self::$INSTANCE;
    }

    public function openConnection()
    {
        if (is_null($this->link)) {
            $this->link = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
            $this->link->query("SET NAMES utf8");
            if (mysqli_connect_errno()) {
                $this->link = null;
            } else {
                //TODO: Replace with error API
                mysqli_report(MYSQLI_REPORT_ERROR);
                //
            }
        }
        return $this->link;
    }

    public function closeConnection()
    {
        if (!is_null($this->link)) {
            $this->link->close();
        }
    }


    private function checkData($data)
    {
        //TODO: Check input data
        return true;
    }

    public function getAssocArray($result = null)
    {
        if($result == null)
            $result = $this->result;
        $arr = array();

        while($data = mysqli_fetch_assoc($result))
            $arr[] = $data;

        return $arr;
    }

    public function getAssoc($result = null)
    {
        if($result != null)
            mysqli_fetch_assoc($result);
        return mysqli_fetch_assoc($this->result);
    }

    public function query($query)
    {   
        // echo $query;
        if(is_null($this->link))
        {
            //TODO: Replace with error API
            echo "ERROR: Querry error, DB is not connected";
            die();
            //
        }
        $this->result = $this->link->query($query);
        return $this->result;
    }

    public function select($table, $columns, $where = null, $orderBy = null, $limit = null)
    {
        $query = "SELECT ".implode(",", $columns)." FROM ".$table;

        if(isset($where))
            $query = $query." WHERE ".$where;
        if(isset($orderBy))
            $query = $query." ORDER BY ".$orderBy;
        if(isset($limit))
            $query = $query." LIMIT ".$limit;

        return $this->query($query);
    }

    public function insert($table, $key_value)
    {
        if(!isset($table) || !isset($key_value) || count($key_value) == 0)
            return;

        $query = "INSERT INTO ".$table." (".implode(",", array_keys($key_value)).")";
        $query = $query." VALUES (";
        foreach(array_values($key_value) as &$value)
        {
            if(!is_numeric($value))
                $query = $query."'".$value."'";
            else 
                $query = $query.$value;
            $query = $query.",";
        }
        $query = substr($query, 0, -1).")"; // Remove last , and add )

        return $this->query($query);
    }

    public function update($table, $key_value, $where = null)
    {
        if(!isset($table) || !isset($key_value) || count($key_value) == 0)
            return;

        $query = "UPDATE ".$table." SET ";
        foreach($key_value as $key => $value)
        {
            $query = $query.$key."=";
            if(!is_numeric($value))
                $query = $query."'".$value."'";
            else 
                $query = $query.$value;
            $query = $query.",";
        }
        $query = substr($query, 0, -1); // Remove last ,

        if(isset($where))
            $query = $query." WHERE ".$where;

        return $this->query($query);
    }

    public function delete($table, $where) 
    {
        $query = "DELETE FROM ".$table." WHERE ".$where;
        return $this->query($query);
    }
}


?>