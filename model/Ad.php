<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;

/**
 * Class Ad
 * @package model
 */
class Ad extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'ad');
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));
        $this->setField('cid', new IntegerField('cid'));
        $this->setField('sid', new IntegerField('sid'));
        $this->setField('purchase', new IntegerField('purchase'));
        $this->setField('price', new IntegerField('price'));
        $this->setField('pid', new IntegerField('pid'));
        $this->setField('created_at', new TimestampField('created_at'));
    }

    public function getAdById($id) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `id` = '$id' LIMIT 1;";
        return $this->query();
    }
}