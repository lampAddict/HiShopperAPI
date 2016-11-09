<?php

namespace ctl;

use core\JsonRequest;
use model\User;

/**
 * Class UserCtl
 * @package ctl
 */
class UserCtl extends Ctl{
    
    use \_traits\Auth;
    
    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }

    /**
     * Get user profile information
     * 
     * @return \core\JsonResponse
     */
    public function profile(){
        
        if( $this->checkUserToken() ){
            $u = new User();
            $this->response->result = $u->getUserByFieldVal('id', $this->userId)->toArray()[0];

            return $this->response;
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }
    }
    
    public function publicProfile(){

        if( $this->checkUserToken() ){
            $u = new User();
            $this->response->result = $u->getUserByFieldVal('id', intval($_GET['id']))->toArray()[0];

            return $this->response;
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }
    }
}