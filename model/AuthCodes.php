<?php

namespace model;

use core\Dispatcher;

use lib\model\TimestampField;
use lib\MySQLDbObject;
use lib\model\IntegerField;

/**
 * Class AuthCodes
 * @package model
 */
class AuthCodes extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'authcodes');
        $this->setField('uid', new IntegerField('uid'));
        $this->setField('code', new IntegerField('code'));
        $this->setField('created_at', new TimestampField('created_at'));
    }

    public function generateCode(){
        $this->code = mt_rand(1000, 9999);
    }
    
    public function getCode(){
        return $this->code;
    }
    
    public function saveCode($uid){
        $this->sql = "INSERT INTO ".$this->table." (`uid`, `code`, `created_at`) VALUES ($uid, ".$this->code.", ".time().");";
        return $this->query();
    }
}