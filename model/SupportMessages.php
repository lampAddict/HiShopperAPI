<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;
use lib\model\TimestampField;

/**
 * Class SupportMessages
 * @package model
 */
class SupportMessages extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'support_messages');
        $this->setField('id', new IntegerField('id'));
        $this->setField('uid', new IntegerField('cid'));
        $this->setField('message', new StringField('message'));
        $this->setField('sid', new IntegerField('sid'));
        $this->setField('created_at', new TimestampField('created_at'));
        $this->setField('updated_at', new TimestampField('updated_at'));

        //грязный хак для join запроса
        $this->setField('msg', new StringField('msg'));
    }

    /**
     * Add user message to support
     * @param $uid integer user identification number
     * @param $msg string user message
     * 
     * @return \lib\model\ObjectCollection
     */
    public function addSupportMessage($uid, $msg) {
        $this->sql = "INSERT INTO `".$this->table."` VALUES(DEFAULT, $uid, '$msg', 0, DEFAULT, DEFAULT);";
        $this->query();

        return $this->getLastInsertedId();
    }

    /**
     * Link solution to user support message
     * @param $msgId integer user message id
     * @param $sid integer solution id
     *
     * @return \lib\model\ObjectCollection
     */
    public function addSupportSolution($msgId, $sid){
        $this->sql = "UPDATE `".$this->table."` SET `sid` = $sid WHERE `id` = $msgId LIMIT 1;";
        return $this->query();
    }

    /**
     * Get list of user support messages with solutions in descending order by user message creation time
     * @param $uid integer user id
     *
     * @return \lib\model\ObjectCollection
     */
    public function getUserChatSupport($uid){
        $this->sql = "SELECT sm.message, ss.msg FROM `".$this->table."` as sm LEFT JOIN `support_solutions` as ss ON sm.sid = ss.id WHERE sm.uid = $uid ORDER BY sm.created_at DESC;";
        return $this->query();
    }
}