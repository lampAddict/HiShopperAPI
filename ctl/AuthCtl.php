<?php

namespace ctl;

use core\JsonRequest;

use model\User;
use model\AuthCodes;
use model\UserTokens;

use lib\StringUtils;

/**
 * Class AuthCtl
 * @package ctl
 */
class AuthCtl extends Ctl{

    use \_traits\Auth;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
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
        $u = $usr->getUserByFieldVal('phone', addslashes($this->request->phone))->toArray();

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

        $this->response->result = ['user'=>$u['id'], 'sms'=>$authCode->getGeneratedCode()];

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
        $u = $usr->getUserByFieldVal('id', addslashes($this->request->user))->toArray();

        if( !empty($u) ){
            $u = $u[0];
            $authCode = new AuthCodes();
            $cc = $authCode->getCode($u['id'], intval($this->request->code))->toArray();
            if( empty($cc) ){
                $this->response->result = null;
                $this->response->errors[] = 'wrong_code';

                return $this->response;
            }
            else{
                $pt = md5((intval($this->request->code)*intval($this->request->code)).$this->request->code);
                $this->response->result = ['pt'=>$pt];

                $ut = new UserTokens();
                $res = $ut->saveToken($u['id'], $pt);

                return $this->response;
            }
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'user_not_found_by_user_id';

            return $this->response;
        }
    }

    public function setpt(){
        if( !isset($this->request->pt) ){
            $this->response->result = null;
            $this->response->errors[] = 'empty_push_token';

            return $this->response;
        }
        
        if( $this->checkUserToken() ){
            $ut = new UserTokens();
            $ut->updateToken($this->userId, $this->request->pt);
            $this->response->result = 'ok';

            return $this->response;
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }
    }
}