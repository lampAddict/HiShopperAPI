<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class User
 * @package model
 */
class User extends MySQLDbObject{
    function __construct() {
        
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user');
        
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));
        $this->setField('phone', new IntegerField('phone'));

        $this->setField('blocked', new IntegerField('blocked'));
        
        //$this->createTable();
    }

    /**
     * @param $uid
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserByFieldVal($field, $val) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `$field` = $val LIMIT 1;";
        return $this->query();
    }

    /**
     * @return \lib\model\ObjectCollection
     */
    public function getAll(){
        $this->sql = 'SELECT * FROM ' . $this->table . ';';
        return $this->query();
    }
}