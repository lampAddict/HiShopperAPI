<?php

namespace config;

use core\IRequestHandler;
use core\JsonRequestHandler;
use lib\MySQLDbEngine;

class Config {
    /**
     * @var IRequestHandler[]
     */
    public $handlers;

    /**
     * @var MySQLDbEngine
     */
    public $engine;
    
    const DBNAME = 'hiShopper';

    function __construct() {
        $this->handlers = array(
            new JsonRequestHandler([
                 'auth_phone' => '\ctl\AuthCtl::phone'
                ,'auth_verify' => '\ctl\AuthCtl::verify'

                ,'auth_setpt' => '\ctl\AuthCtl::setpt'
                ,'user_profile' => '\ctl\UserCtl::profile'
                ,'user_public' => '\ctl\UserCtl::publicProfile'
                ,'user_publicDeals' => '\ctl\UserCtl::publicProfileDeals'
                ,'user_publicAds' => '\ctl\UserCtl::publicProfileAds'
                ,'user_nickname' => '\ctl\UserCtl::checkNickname'
                ,'user_update' => '\ctl\UserCtl::updateProfile'
                ,'user_follow' => '\ctl\UserCtl::follow'
            ])
        );

        $this->engine = new MySQLDbEngine( 'localhost', 'root', '', Config::DBNAME, $pre='' );
    }
} 