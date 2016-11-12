<?php

namespace ctl;

use core\JsonRequest;

use model\User;
use model\UserAds;
use model\UserDeals;
use model\UserPhotos;
use model\UserFollowers;

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
            $uInfo = $u->getUserByFieldVal('id', $this->userId)->toArray()[0];

            //Соберем адрес в отдельное поле
            $fields = ['index'=>'cindex', 'street'=>'street', 'house'=>'house', 'flat'=>'flat'];
            $uInfo['address'] = [];
            foreach ($fields as $fld=>$dbfld){
                $uInfo['address'][$fld] = $uInfo[$dbfld];
                unset($uInfo[$dbfld]);
            }
            $uInfo['address']['fio'] = $uInfo['name'];

            $this->response->result = $uInfo;

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

            $uid = intval($_GET['id']);

            $u = new User();
            $uInfo = $u->getUserByFieldVal('id', $uid)->toArray();
            if( empty($uInfo) ){
                $this->response->result = null;
                $this->response->errors[] = 'user_not_found';

                return $this->response;
            }
            $uInfo = $uInfo[0];

            //Объявления пользователя
            $uAds = new UserAds();
            $uInfo['ads'] = $uAds->getUserAds($uid)->toArray();

            //Сделки пользователя
            $uDeals = new UserDeals();
            $uInfo['deals'] = $uDeals->getUserDeals($uid)->toArray();

            //Фотографии пользователя
            $uPhotos = new UserPhotos();
            $uInfo['photos'] = $uPhotos->getUserPhotos($uid)->toArray();

            //Последователи
            $uFollowers = new UserFollowers();
            $uInfo['followers'] = count($uFollowers->getUserFollowers($uid)->toArray());
            $uInfo['followed']  = (!empty($uFollowers->checkUserFollowing($uid, $this->userId)->toArray()) ? 1 : 0);

            //Уберём лишние данные
            $fields = ['cindex', 'street', 'house', 'flat', 'gender', 'phone', 'email', 'blocked'];
            foreach ($fields as $fld){
                unset($uInfo[$fld]);
            }

            $this->response->result = $uInfo;

            return $this->response;
        }
        else{
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }
    }
}