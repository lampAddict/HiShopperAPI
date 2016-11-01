<?php

namespace model;

use core\Dispatcher;
use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class Rates
 * @package model
 */
class Rates extends MySQLDbObject{
    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'rates');
        $this->setField('id', new IntegerField('id'));
        $this->setField('rname', new StringField('rname'));
        //$this->createTable();
    }

    /**
     * @param $rid
     *
     * @return \lib\model\ObjectCollection
     */
    public function getRates($rid) {
        if( !empty($rid) ){
            $where = ' IN (' . join(',',$rid) . ')';
        }
        else{
            $where = ' = \''.$rid.'\' LIMIT 1';
        }
        $this->sql = 'SELECT * FROM '.$this->table .' WHERE id '.$where.' ;';
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