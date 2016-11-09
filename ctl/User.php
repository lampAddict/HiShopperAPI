<?php

namespace ctl;

use core\JsonRequest;

/**
 * Class Auth
 * @package ctl
 */
class User extends Ctl{
    
    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }
    
    public function profile(){
        
    }
}