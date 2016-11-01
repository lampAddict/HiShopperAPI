<?php

namespace model;

use core\Dispatcher;
use lib\MySQLDbObject;
use lib\model\IntegerField;

/**
 * Class UserRates
 * @package model
 */
class UserRates extends MySQLDbObject{
    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_rates');
        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('rid', new IntegerField('rid'));
        //$this->createTable();
    }

    /**
     * @param $rid
     *
     * @return \lib\model\ObjectCollection
     */
    public function getRatesIdsByUserId($rid) {
        $this->sql = 'SELECT * FROM '.$this->table .' WHERE uid = \''.$rid.'\';';
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