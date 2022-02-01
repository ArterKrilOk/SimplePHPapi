<?php

class TestModel extends DBModel{
    public $id;
    public $name;
    public $create_date;
    public $update_date;

    public function __construct() {
        parent::__construct('testModel');
    }

    public static function fromDB($primaryKeyValue)
    {
        return parent::fromDBStatic($primaryKeyValue, get_called_class(), 'testModel', 'id');
    }

    public static function create($name)
    {
        $file = new TestModel();
        $file->name = $name;
        $file->insert();

        return parent::fromDBStatic($name, get_called_class(), 'testModel', 'name');
    }
}