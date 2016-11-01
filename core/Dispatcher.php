<?php

namespace core;

use config\Config;

/**
 * Class Dispatcher
 * @package core
 */
class Dispatcher extends Singletone{

    public $request = '';

    /**
     * @var Config
     */
    private $config;

    /**
     * @return \config\Config
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     *
     */
    protected function __construct(){
        $this->config = new Config();
    }

    /**
     * @throws \Exception
     */
    public function main(){

        $handled = false;

        foreach( $this->config->handlers as $handler ){
            $handled = $handler->handle();
            if ($handled) break;
        }

        if( !$handled ){
            throw new \Exception("Request handler not found.");
        }
    }
}