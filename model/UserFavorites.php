<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;

/**
 * Class UserFavorite
 * @package model
 */
class UserFavorites extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_favorites');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('aid', new IntegerField('aid'));
    }

    /**
     * Get list of user favorites advertisement ids
     * @param $uid integer user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserFavorites($uid) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }

    /**
     * Add ad to user favorites
     * @param $uid integer user identification number
     * @param $aid integer ad identification number
     * 
     * @return \lib\model\ObjectCollection
     */
    public function addUserFavorite($uid, $aid){
        $this->sql = "INSERT INTO `".$this->table."` VALUES (DEFAULT, $uid, $aid);";
        return $this->query();
    }

    /**
     * Removes ad from user favorites
     * @param $uid integer user identification number
     * @param $aid integer ad identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function removeUserFavorite($uid, $aid){
        $this->sql = "DELETE FROM `".$this->table."` WHERE `uid` = $uid AND `aid` = $aid LIMIT 1;";
        return $this->query();
    }
}