<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;

/**
 * Class UserAds
 * @package model
 */
class UserBrands extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_brands');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('bid', new IntegerField('bid'));
    }

    /**
     * Get list of user brands ids
     * @param $uid user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserBrands($uid) {
        $this->sql = "SELECT `bid` FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }
}