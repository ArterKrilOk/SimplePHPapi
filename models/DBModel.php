<?php


class DBModel {
    private $table;
    private $db;
    private $primaryKeyName;
    
    public function __construct($table = null, $primaryKeyName = null, $db = null)
    {
        if($db == null)
            $this->db = DBWorker::getDefInstance();
        else
            $this->db = $db;

        if($table == null)
            $this->table = strtolower(get_class($this));
        else
            $this->table = $table;

        if($primaryKeyName == null)
            $this->primaryKeyName = 'id';
        else
            $this->primaryKeyName = $primaryKeyName;
    }

    public function insert()
    {
        $this->db->insert($this->table, $this->getProperties(true));
    }

    public function update()
    {   
        $props = $this->getProperties(true);
        $primaryKey = $props[$this->primaryKeyName];
        $this->db->update($this->table, $props, $this->primaryKeyName . '=' . $primaryKey);
    }

    public function getFromDB($primaryKeyValue) 
    {
        if(!is_numeric($primaryKeyValue))
            $primaryKeyValue = "'" . $primaryKeyValue . "'";

        $this->db->select(
            $this->table,
            array_keys($this->getProperties()),
            $this->primaryKeyName . '=' . $primaryKeyValue,
            null,
            1
        );
        foreach($this->db->getAssoc() as $key=>$val){
            $this->$key = $val;
        }
    }

    protected static function fromDBStatic($primaryKeyValue, $className = null, $table = null, $primaryKeyName = null) 
    {
        if($className == null)
            $className = get_called_class();
        if($primaryKeyName == null)
            $primaryKeyName = 'id';
        if($table == null)
            $table = strtolower($className);
        if(!is_numeric($primaryKeyValue))
            $primaryKeyValue = "'" . $primaryKeyValue . "'";

        $props = DBModel::getStaticClassProperties($className);
        
        $db = DBWorker::getDefInstance();
        $db->select(
            $table,
            $props,
            $primaryKeyName . '=' . $primaryKeyValue,
            null,
            1
        );

        $object = new $className();
        $assoc = $db->getAssoc();

        if($assoc == null)
            return $assoc;
        
        foreach($assoc as $key=>$val){
            $object->$key = $val;
        }
        return $object;
    }

    public static function getObjects($where = null, $orderBy = null, $limit = null, $className = null, $table = null)
    {
        if($className == null)
            $className = get_called_class();
        if($table == null)
            $table = strtolower($className);

        $db = DBWorker::getDefInstance();
        $props = DBModel::getStaticClassProperties($className);

        $db->select(
            $table,
            $props,
            $where,
            $orderBy,
            $limit
        );

        $assoc = $db->getAssocArray();

        $objects = array();

        foreach($assoc as $a)
        {
            $object = new $className();
            foreach($a as $key=>$val)
            {
                $object->$key = $val;
            }
            $objects[] =  $object;
        }
        return $objects;
    }

    private static function getStaticClassProperties($className)
    {
        $props = array();
        $ref = new ReflectionClass($className);
        foreach ($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $prop)
            $props[] = $prop->name;
        
        return $props;
    }

    private function getProperties($ignoreEmpty = false)
    {
        $props = array();
        $ref = new ReflectionClass(get_class($this));
        foreach ($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $prop)
            if($ignoreEmpty)
            {
                if(isset(get_object_vars($this)[$prop->name]))
                    $props[$prop->name] = get_object_vars($this)[$prop->name];
            } else {
                $props[$prop->name] = get_object_vars($this)[$prop->name];
            }
        
        return $props;
    }
}