<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;

/**
 * Class UserAds
 * @package model
 */
class UserAds extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_ads');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('info', new StringField('info'));
        $this->setField('price', new IntegerField('price'));
        $this->setField('created_at', new TimestampField('created_at'));
        $this->setField('updated_at', new TimestampField('updated_at'));
    }

    /**
     * Get list of user ads
     * @param $uid integer user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserAds($uid) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }
}