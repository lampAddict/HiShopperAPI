<?php

namespace ctl;

use core\JsonRequest;

use model\User;
use model\AuthCodes;
use model\UserTokens;

use lib\StringUtils;

/**
 * Class Auth
 * @package ctl
 */
class Auth extends Ctl{

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
        $u = $usr->getUserByFieldVal('id', $this->request->user)->toArray();

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

        $headers = getallheaders();
        if( isset($headers['x-auth']) ){

            $ut = new UserTokens();
            $uid = $ut->getUserByToken(addslashes($headers['x-auth']))->toArray();
            if( !empty($uid) ){
                $uid = $uid[0]['uid'];
                $ac = new AuthCodes();
                if( $ac->checkCode($uid, $headers['x-auth']) ){
                    $ut->updateToken($uid, $this->request->pt);
                    $this->response->result = 'ok';

                    return $this->response;
                }
            }
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }
    }
}