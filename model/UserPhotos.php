<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;

/**
 * Class UserPhotos
 * @package model
 */
class UserPhotos extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_photos');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('photo', new StringField('photo'));
        $this->setField('created_at', new TimestampField('created_at'));
        $this->setField('updated_at', new TimestampField('updated_at'));
    }

    /**
     * Get list of user photos
     * @param $uid integer user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserPhotos($uid) {
        $this->sql = "SELECT `photo` FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }
}