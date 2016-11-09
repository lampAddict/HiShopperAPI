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
                 'auth_phone' => '\ctl\Auth::phone'
                ,'auth_verify' => '\ctl\Auth::verify'

                ,'auth_setpt' => '\ctl\Auth::setpt'
                ,'user_profile' => '\ctl\User::profile'
            ])
        );

        $this->engine = new MySQLDbEngine( 'localhost', 'root', '', Config::DBNAME, $pre='' );
    }
} 