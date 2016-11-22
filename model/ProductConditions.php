<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;


/**
 * Class ProductConditions
 * @package model
 */
class ProductConditions extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'product_conditions');
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));
    }

    /**
     * Get all possible products conditions advertisement by its identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getAllPossibleConditions() {
        $this->sql = "SELECT `id`, `name` FROM `".$this->table."`;";
        return $this->query();
    }
}