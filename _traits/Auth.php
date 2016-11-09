<?php

namespace _traits;

use model\UserTokens;
use model\AuthCodes;

trait Auth{
    
    public $userId = null;
    
    /**
     * Checks if user has authorized
     * 
     * @return integer|bool user identification number on successful check false otherwise
     */
    function checkUserToken(){
        $headers = getallheaders();
        if( isset($headers['x-auth']) ){
            $ut = new UserTokens();
            $uid = $ut->getUserByToken(addslashes($headers['x-auth']))->toArray();
            if( !empty($uid) ){
                $this->userId = $uid[0]['uid'];
                $ac = new AuthCodes();
                if( $ac->checkCode($this->userId, $headers['x-auth']) ){
                    return true;
                }
            }
            return false;
        }
        else{
            return false;
        }
    }
}