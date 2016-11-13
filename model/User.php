<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class User
 * @package model
 */
class User extends MySQLDbObject{
    function __construct() {
        
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user');
        
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));
        $this->setField('nickname', new StringField('nickname'));
        $this->setField('phone', new StringField('phone'));
        $this->setField('email', new StringField('email'));
        $this->setField('gender', new StringField('gender'));
        $this->setField('cindex', new StringField('cindex'));
        $this->setField('city', new StringField('city'));
        $this->setField('street', new StringField('street'));
        $this->setField('house', new StringField('house'));
        $this->setField('flat', new IntegerField('flat'));

        $this->setField('blocked', new IntegerField('blocked'));
    }

    /**
     * Get value of particular field in user profile
     * @param $field string field name
     * @param $val string value
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserByFieldVal($field, $val) {
        $this->sql = "SELECT * FROM `".$this->table."` WHERE `$field` = '$val' LIMIT 1;";
        return $this->query();
    }

    /**
     * Get name of user profile picture 
     * @param $uid integer user identification number
     * 
     * @return \lib\model\ObjectCollection
     */
    public function getUserPhoto($uid){
        $this->sql = "SELECT * FROM `user_photos` WHERE `uid` = '$uid' LIMIT 1;";
        return $this->query();
    }

    /**
     * Update user profile picture
     * @param $uid integer user identification number
     * @param $photo string file name
     * 
     * @return \lib\model\ObjectCollection
     */
    public function updateUserPhoto($uid, $photo){
        $this->sql = "UPDATE `user_photos` SET `photo` = '$photo' WHERE `uid` = '$uid' LIMIT 1;";
        return $this->query();
    }

    /**
     * Save user profile picture name to DB
     * @param $uid integer user identification number
     * @param $photo string file name
     * 
     * @return \lib\model\ObjectCollection
     */
    public function saveUserPhoto($uid, $photo){
        if( !empty($this->getUserPhoto($uid)->toArray()) ){
            return $this->updateUserPhoto($uid, $photo);
        }
        else{
            $this->sql = "INSERT INTO `user_photos` VALUES (DEFAULT, '$uid', '$photo', DEFAULT, DEFAULT);";
            return $this->query();
        }
    }
}