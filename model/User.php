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
        $this->setField('nickname', new StringField('nickname'));
        $this->setField('phone', new StringField('phone'));
        $this->setField('email', new StringField('email'));
        $this->setField('gender', new StringField('gender'));
        $this->setField('cindex', new StringField('cindex'));
        $this->setField('city', new StringField('city'));
        $this->setField('street', new StringField('street'));
        $this->setField('house', new StringField('house'));
        $this->setField('flat', new IntegerField('flat'));

        $this->setField('blocked', new IntegerField('blocked'));
    }

    /**
     * @param $field
     * @param $val
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserByFieldVal($field, $val) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `$field` = $val LIMIT 1;";
        return $this->query();
    }
}