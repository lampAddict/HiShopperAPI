<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;

/**
 * Class UserDeals
 * @package model
 */
class UserDeals extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_deals');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uids', new IntegerField('uids'));//Seller
        $this->setField('uidc', new StringField('uidc'));//Client
        $this->setField('created_at', new TimestampField('created_at'));
    }

    /**
     * Get list of user deals
     * @param $uid integer user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserDeals($uid) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `uidc` = $uid;";
        return $this->query();
    }
}