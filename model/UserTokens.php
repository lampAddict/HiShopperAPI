<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class UserTokens
 * @package model
 */
class UserTokens extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_tokens');

        $this->setField('uid', new IntegerField('uid'));
        $this->setField('token', new StringField('token'));
    }

    /**
     * Get user id by its token
     * @param $pt string push-token
     * 
     * @return \lib\model\ObjectCollection
     */
    public function getUserByToken($pt){
        $this->sql = "SELECT `uid` FROM `".$this->table."` WHERE `token` = '$pt' LIMIT 1;";
        return $this->query();
    }

    /**
     * Get user token by its id
     * @param $uid integer user id
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserToken($uid){
        $this->sql = "SELECT `token` FROM `".$this->table."` WHERE `uid` = '$uid' LIMIT 1;";
        return $this->query();
    }

    /**
     * Save unique user token to DB update otherwise
     * @param $uid integer user id
     * @param $pt string push-token
     * 
     * @return \lib\model\ObjectCollection
     */
    public function saveToken($uid, $pt){

        if( !empty($this->getUserToken($uid)) ){
            $this->updateToken($uid, $pt);
        }
        else{
            $this->sql = "INSERT INTO ".$this->table." (`id`, `uid`, `token`) VALUES (DEFAULT, $uid, '$pt');";
            return $this->query();
        }
    }

    /**
     * Update user token in DB
     * @param $uid integer user id
     * @param $pt string push-token
     *
     * @return \lib\model\ObjectCollection
     */
    public function updateToken($uid, $pt){
        $this->sql = "UPDATE ".$this->table." SET `token` = '$pt' WHERE `uid` = '$uid' LIMIT 1;";
        return $this->query();
    }
}