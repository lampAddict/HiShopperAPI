<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;


/**
 * Class SupportSolutions
 * @package model
 */
class SupportSolutions extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'support_solutions');
        $this->setField('id', new IntegerField('id'));
        $this->setField('msg', new StringField('msg'));
        $this->setField('created_at', new TimestampField('created_at'));
    }

    /**
     * Add solution to user message to support
     * @param $msg string solution
     *
     * @return \lib\model\ObjectCollection
     */
    public function addSupportSolution($msg) {
        $this->sql = "INSERT INTO `".$this->table."` VALUES(DEFAULT, '$msg');";
        $this->query();

        return $this->getLastInsertedId();
    }
}