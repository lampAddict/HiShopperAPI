<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;

/**
 * Class UserAds
 * @package model
 */
class UserSections extends MySQLDbObject{
    function __construct() {

        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'user_sections');

        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('sid', new IntegerField('sid'));
    }

    /**
     * Get list of user sections ids
     * @param $uid integer user identification number
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserSections($uid) {
        $this->sql = "SELECT `sid` FROM `".$this->table."` WHERE `uid` = $uid;";
        return $this->query();
    }
}