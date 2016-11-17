<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class Settings
 * @package model
 */
class Settings extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'settings');
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));
        $this->setField('value', new StringField('value'));
    }

    /**
     * Get settings from DB
     *
     * @return \lib\model\ObjectCollection
     */
    public function getSettings(){
        $this->sql = "SELECT `name`, `value` FROM `".$this->table."` ORDER BY `id` DESC;";
        return $this->query();
    }
}