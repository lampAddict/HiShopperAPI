<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;


/**
 * Class UserFollowers
 * @package model
 */
class UserFollowers extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_followers');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('uidf', new IntegerField('uidf'));
    }

    /**
     * Get list of followers
     * @param $uid user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserFollowers($uid) {
        $this->sql = "SELECT `uidf` FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }

    /**
     * Check if one user is following another
     * @param $uid user identification number
     * @param $uidf user follower identification number
     * 
     * @return \lib\model\ObjectCollection
     */
    public function checkUserFollowing($uid, $uidf) {
        $this->sql = "SELECT `id` FROM `".$this->table."` WHERE `uid` = $uid AND `uidf` = $uidf LIMIT 1;";
        return $this->query();
    }
}