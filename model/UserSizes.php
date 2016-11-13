<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class UserAds
 * @package model
 */
class UserSizes extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_sizes');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('size', new StringField('size'));
    }

    /**
     * Get list of user sizes
     * @param $uid user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserSizes($uid) {
        $this->sql = "SELECT `size` FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }
}