<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;


/**
 * Class ProductColors
 * @package model
 */
class ProductColors extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'product_colors');
        $this->setField('id', new IntegerField('id'));
        $this->setField('hex', new StringField('hex'));
        $this->setField('name', new StringField('name'));
    }

    /**
     * Get all possible product colors
     *
     * @return \lib\model\ObjectCollection
     */
    public function getColors() {
        $this->sql = "SELECT `id`, `hex`, `name` FROM `".$this->table."`;";
        return $this->query();
    }
}