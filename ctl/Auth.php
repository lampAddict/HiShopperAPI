<?php

namespace ctl;

use core\Dispatcher;
use core\JsonRequest;
use core\JsonResponse;

use model\User;
use model\AuthCodes;

use lib\StringUtils;

/**
 * Class Auth
 * @package ctl
 */
class Auth {
    
    /**
     * @var JsonRequest
     */
    public $request;

    /**
     * @var JsonResponse
     */
    public $response;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        
        $this->request = $request;
        $this->response = $this->request->response();
    }
    
    public function phone(){

        if( !isset($this->request->phone) ){
            $this->response->result = null;
            $this->response->errors[] = 'empty_phone';

            return $this->response;
        }

        if( !StringUtils::CheckPhoneNumberFormat($this->request->phone) ){
            $this->response->result = null;
            $this->response->errors[] = 'wrong_phone_format';

            return $this->response;
        }

        $usr = new User();
        $u = $usr->getUserByFieldVal('phone', $this->request->phone)->toArray();

        if( !empty($u) ){
            $u = $u[0];
            if( $u['blocked'] == 1 ){
                $this->response->result = null;
                $this->response->errors[] = 'user_blocked';

                return $this->response;
            }
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'user_not_found';
            
            return $this->response;
        }
        
        $authCode = new AuthCodes();
        $authCode->generateCode();
        $authCode->saveCode($u['id']);

        $this->response->result = ['user'=>$u['id'], 'sms'=>$authCode->getCode()];

        //echo var_export( $usr, true); die;
        return $this->response;
    }

    public function verify(){

        if( !isset($this->request->user) ){
            $this->response->result = null;
            $this->response->errors[] = 'empty_user';

            return $this->response;
        }

        $usr = new User();
        $u = $usr->getUserByFieldVal('id', $this->request->user)->toArray();

        if( !empty($u) ){
            $u = $u[0];
            $authCode = new AuthCodes();
            $cc = $authCode->checkUserCode($u['id'], intval($this->request->code))->toArray();
            if( empty($cc) ){
                $this->response->result = null;
                $this->response->errors[] = 'wrong_code';

                return $this->response;
            }
            else{
                
            }
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'user_not_found_by_user_id';

            return $this->response;
        }
    }
}