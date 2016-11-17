<?php

namespace ctl;

use core\JsonRequest;

use model\SupportMessages;
use model\User;
use model\UserAds;
use model\UserBrands;
use model\UserDeals;
use model\UserPhotos;
use model\UserSizes;
use model\UserSections;
use model\UserFollowers;
use model\Ad;
use model\UserFavorites;

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

        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $this->response->result = $this->getUserProfileData($this->userId);

        return $this->response;
    }

    protected function getUserProfileData($uid){

        $u = new User();
        $uInfo = $u->getUserByFieldVal('id', $uid)->toArray()[0];

        //Фотографии пользователя
        $uPhotos = new UserPhotos();
        $uPhoto = $uPhotos->getUserPhotos($uid)->toArray();
        if( !empty($uPhoto) ){
            $uInfo['photo'] = [$uPhoto[0]['photo']];
        }
        else{
            $uInfo['photo'] = [];
        }

        //Брэнды пользователя
        $uBrands = new UserBrands();
        $uBrand = $uBrands->getUserBrands($uid)->toArray();
        if( !empty($uBrand) ){
            foreach ($uBrand as $bbid){
                $uInfo['brands'][] = $bbid['bid'];
            }
        }
        else{
            $uInfo['brands'] = [];
        }

        //Размеры пользователя
        $uSizes = new UserSizes();
        $uSize = $uSizes->getUserSizes($uid)->toArray();
        if( !empty($uSize) ){
            foreach ($uSize as $us){
                $uInfo['sizes'][] = intval($us['size']);
            }
        }
        else{
            $uInfo['sizes'] = [];
        }

        //Разделы пользователя
        $uSections = new UserSections();
        $uSection = $uSections->getUserSections($uid)->toArray();
        if( !empty($uSection) ){
            foreach ($uSection as $us){
                $uInfo['sections'][] = $us['sid'];
            }
        }
        else{
            $uInfo['sections'] = [];
        }

        //Последователи пользователя
        $uFollowers = new UserFollowers();
        $uFollower = $uFollowers->getUserFollowers($uid)->toArray();
        if( !empty($uFollower) ){
            foreach ($uFollower as $uf){
                $uInfo['follow']['followers'][] = $uf['uidf'];
            }
        }

        //Те кого пользователь фолловит
        $uPublisher = $uFollowers->getUserPublishers($uid)->toArray();
        if( !empty($uPublisher) ){
            foreach ($uPublisher as $up){
                $uInfo['follow']['publishers'][] = $up['uid'];
            }
        }

        //Соберем адрес в отдельное поле
        $fields = ['index'=>'cindex', 'street'=>'street', 'house'=>'house', 'flat'=>'flat'];
        $uInfo['address'] = [];
        foreach ($fields as $fld=>$dbfld){
            $uInfo['address'][$fld] = $uInfo[$dbfld];
            unset($uInfo[$dbfld]);
        }
        $uInfo['address']['fio'] = $uInfo['name'];

        return $uInfo;
    }

    public function publicProfile(){

        if( !$this->checkUserToken() ) {
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

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

    public function publicProfileDeals(){

        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $uid = intval($_GET['id']);

        //Сделки пользователя
        $uDeals = new UserDeals();
        $this->response->result = $uDeals->getUserDeals($uid)->toArray();

        return $this->response;
    }

    public function publicProfileAds(){

        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $uid = intval($_GET['id']);

        $uAds = new UserAds();
        $this->response->result = $uAds->getUserAds($uid)->toArray();

        return $this->response;
    }

    public function checkNickname(){

        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $u = new User();
        $used = $this->doCheckNickname($u, addslashes($this->request->nickname));

        if( !empty($used) ){
            $this->response->result['used'] = 1;
        }
        else{
            $this->response->result['used'] = 0;
        }

        return $this->response;
    }

    protected function doCheckNickname(User $u, $nickname){
        return $u->getUserByFieldVal('nickname', $nickname)->toArray();
    }

    public function updateProfile(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $u = new User();
        //update profile routine, request contains picture
        $updateUserProfileResult = false;
        if( isset($_POST['data']) ){
            $data = json_decode($_POST['data'], true);

            //check user nickname availability
            if(    isset($data['nickname'])
                && !empty($this->doCheckNickname($u, $data['nickname']))
            ){
                $this->response->result = null;
                $this->response->errors[] = 'nickname_already_exists';

                return $this->response;
            }

            //delete user profile picture
            if(    isset($data['photo'])
                && $data['photo'] == 'delete'
            ){
                $u->deleteUserPhoto($this->userId);
                unset($data['photo']);

                $this->response->result = $this->getUserProfileData($this->userId);
                return $this->response;
            }

            $updateUserProfileResult = $u->updateUserProfile($this->userId, $data);
        }

        //update profile routine, common request without picture
        if( $updateUserProfileResult === false ){
            $props = get_object_vars($this->request);
            if( count($props) > 2 ){
                unset($props['data']);
                unset($props['requestName']);

                //check user nickname availability
                if(    isset($props['nickname'])
                    && !empty($this->doCheckNickname($u, $props['nickname']))
                ){
                    $this->response->result = null;
                    $this->response->errors[] = 'nickname_already_exists';

                    return $this->response;
                }

                //delete user profile picture
                if(    isset($props['photo'])
                    && $props['photo'] == 'delete'
                ){
                    $u->deleteUserPhoto($this->userId);
                    unset($props['photo']);

                    $this->response->result = $this->getUserProfileData($this->userId);
                    return $this->response;
                }

                $updateUserProfileResult = $u->updateUserProfile($this->userId, $props);
            }
        }

        if( $updateUserProfileResult === false ){
            $this->response->result = null;
            $this->response->errors[] = 'user_profile_update_failed';

            return $this->response;
        }

        $uInfo = $this->getUserProfileData($this->userId);

        if( !empty($_FILES) ){
            $numFiles = count($_FILES);

            for($f=0; $f<$numFiles; $f++){
                if( is_file($_FILES['file-'.$f]['tmp_name']) ){
                    $allowedTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
                    $detectedType = exif_imagetype($_FILES['file-'.$f]['tmp_name']);
                    if( in_array($detectedType, $allowedTypes) ){
                        switch($detectedType){
                            case IMAGETYPE_PNG;
                                $ext = '.png';
                                break;
                            case IMAGETYPE_JPEG;
                                $ext = '.jpg';
                                break;
                            case IMAGETYPE_GIF;
                                $ext = '.gif';
                                break;
                        }
                        $fName = mb_substr(md5(time().$f.$uInfo['nickname']), 0, 10);
                        move_uploaded_file($_FILES['file-'.$f]['tmp_name'], __DIR__.'/../static/img/profile/'.$fName.$ext);

                        $u->saveUserPhoto($this->userId, $fName.$ext);
                        $uInfo['photo'] = $fName.$ext;
                    }
                }
            }
        }

        $this->response->result = $uInfo;

        return $this->response;
    }

    public function follow(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        if( !isset($this->request->publisher) ){
            $this->response->result = null;
            $this->response->errors[] = 'wrong_publisher';

            return $this->response;
        }

        $u = new User();
        $uProfileData = $u->getUserByFieldVal('id', intval($this->request->publisher))->toArray();
        if( empty($uProfileData) ){
            $this->response->result = null;
            $this->response->errors[] = 'publisher_not_found';

            return $this->response;
        }

        $uf = new UserFollowers();
        $up = $uf->getUserPublishers($this->userId)->toArray();
        if( !empty($up) ){
            foreach ($up as $p){
                if( $p['uid'] == intval($this->request->publisher) ){
                    $this->response->result = null;
                    $this->response->errors[] = 'already_followed';

                    return $this->response;
                }
            }
        }

        $uf->addUserFollower(intval($this->request->publisher), $this->userId);

        //Последователи пользователя
        $uFollower = $uf->getUserFollowers($this->userId)->toArray();
        if( !empty($uFollower) ){
            foreach ($uFollower as $_uf){
                $this->response->result['followers'][] = $_uf['uidf'];
            }
        }

        //Те кого пользователь фолловит
        $uPublisher = $uf->getUserPublishers($this->userId)->toArray();
        if( !empty($uPublisher) ){
            foreach ($uPublisher as $up){
                $this->response->result['publishers'][] = $up['uid'];
            }
        }

        return $this->response;
    }

    public function unfollow(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        if( !isset($this->request->publisher) ){
            $this->response->result = null;
            $this->response->errors[] = 'wrong_publisher';

            return $this->response;
        }

        $u = new User();
        $uProfileData = $u->getUserByFieldVal('id', intval($this->request->publisher))->toArray();
        if( empty($uProfileData) ){
            $this->response->result = null;
            $this->response->errors[] = 'publisher_not_found';

            return $this->response;
        }

        $uf = new UserFollowers();
        $up = $uf->getUserPublishers($this->userId)->toArray();
        if( !empty($up) ){
            $exist = false;
            foreach ($up as $p){
                if( $p['uid'] == intval($this->request->publisher) ){
                    $exist = true;
                    break;
                }
            }
            if(!$exist){
                $this->response->result = null;
                $this->response->errors[] = 'not_exists';

                return $this->response;
            }
        }

        $uf->removeUserFollower(intval($this->request->publisher), $this->userId);

        //Последователи пользователя
        $uFollower = $uf->getUserFollowers($this->userId)->toArray();
        if( !empty($uFollower) ){
            foreach ($uFollower as $_uf){
                $this->response->result['followers'][] = $_uf['uidf'];
            }
        }

        //Те кого пользователь фолловит
        $uPublisher = $uf->getUserPublishers($this->userId)->toArray();
        if( !empty($uPublisher) ){
            foreach ($uPublisher as $up){
                $this->response->result['publishers'][] = $up['uid'];
            }
        }

        return $this->response;
    }

    public function favoriteAdd(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        if( !isset($this->request->ad) ){
            $this->response->result = null;
            $this->response->errors[] = 'wrong_ad';

            return $this->response;
        }

        $ad = new Ad();
        $_ad = $ad->getAdById(intval($this->request->ad))->toArray();
        if( empty($_ad) ){
            $this->response->result = null;
            $this->response->errors[] = 'ad_not_found';

            return $this->response;
        }

        $uf = new UserFavorites();
        $_uf = $uf->getUserFavorites($this->userId)->toArray();
        if( !empty($_uf) ){
            foreach ($_uf as $__uf){
                if( $__uf['aid'] == intval($this->request->ad) ){
                    $this->response->result = null;
                    $this->response->errors[] = 'already_favorite';

                    return $this->response;
                }
            }
        }
        
        $uf->addUserFavorite($this->userId, intval($this->request->ad));

        $this->response->result['count'] = count($uf->getUserFavorites($this->userId)->toArray());

        return $this->response;
    }

    public function favoriteRemove(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        if( !isset($this->request->ad) ){
            $this->response->result = null;
            $this->response->errors[] = 'wrong_ad';

            return $this->response;
        }

        $ad = new Ad();
        $_ad = $ad->getAdById(intval($this->request->ad))->toArray();
        if( empty($_ad) ){
            $this->response->result = null;
            $this->response->errors[] = 'ad_not_found';

            return $this->response;
        }

        $uf = new UserFavorites();
        $_uf = $uf->getUserFavorites($this->userId)->toArray();
        if( !empty($_uf) ){
            $inFav = false;
            foreach ($_uf as $__uf){
                if( $__uf['aid'] == intval($this->request->ad) ){
                    $inFav = true;
                    break;
                }
            }

            if( !$inFav ){
                $this->response->result = null;
                $this->response->errors[] = 'not_in_favorites';

                return $this->response;
            }
        }

        $uf->removeUserFavorite($this->userId, intval($this->request->ad));

        $this->response->result['count'] = count($uf->getUserFavorites($this->userId)->toArray());

        return $this->response;
    }

    public function favoriteList(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $this->response->result = [];

        $max = isset($_GET['max']) ? intval($_GET['max']) : 999;
        $count = isset($_GET['count']) ? intval($_GET['count']) : 0;

        $u  = new User();
        $ad = new Ad();
        $uf = new UserFavorites();
        $_uf = $uf->getUserFavorites($this->userId)->toArray();
        if( !empty($_uf) ){
            $num = 0;
            foreach ($_uf as $c=>$__uf){
                if(    $c >= $count
                    && $num < $max
                ){
                    $_ad = $ad->getAdById($__uf['aid'])->toArray();
                    $_ad[0]['user'] = $u->getUserByFieldVal('id', $this->userId)->toArray()[0];
                    $this->response->result[] = ['id'=>$__uf['id'], 'ad'=>$_ad[0], 'can_buy'=>false];

                    $num++;
                }
            }
        }

        return $this->response;
    }

    public function favoriteCount(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $uf = new UserFavorites();
        $_uf = $uf->getUserFavorites($this->userId)->toArray();
        if( !empty($_uf) ){
            $this->response->result['count'] = count($_uf);
        }

        return $this->response;
    }

    public function search(){

        if( isset($_GET['q']) ){

            if( mb_strlen($_GET['q']) < 3 ){
                $this->response->result = null;
                $this->response->errors[] = 'short_query';

                return $this->response;
            }

            $searchFor = addslashes($_GET['q']);
            $max = isset($_GET['max']) ? intval($_GET['max']) : 999;
            $count = isset($_GET['count']) ? intval($_GET['count']) : 0;

            $this->response->result = [];

            $u = new User();
            $_u = $u->searchByNickname($searchFor)->toArray();
            if( !empty($_u) ){

                if(    isset($_GET['type'])
                    && $_GET['type'] == 'count'
                ){
                    $this->response->result['count'] = count($_u);
                }
                else{
                    $num = 0;
                    foreach($_u as $c=>$__u){
                        if(    $c >= $count
                            && $num < $max
                        ){
                            $this->response->result[] = $__u;
                            $num++;
                        }
                    }
                }
            }
        }
        return $this->response;
    }
    
    public function support(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $sm = new SupportMessages();
        $this->response->result['id'] = $sm->addSupportMessage($this->userId, addslashes($this->request->message));

        return $this->response;
    }

    public function supportChat(){
        if( !$this->checkUserToken() ){
            $this->response->result = null;
            $this->response->errors[] = 'not_authorized';

            return $this->response;
        }

        $max = isset($_GET['max']) ? intval($_GET['max']) : 999;
        $count = isset($_GET['count']) ? intval($_GET['count']) : 0;

        $this->response->result = [];

        $sm = new SupportMessages();
        $_sm = $sm->getUserChatSupport($this->userId)->toArray();
        if( !empty($_sm) ){
            $num = 0;
            foreach ($_sm as $c=>$__sm){
                if(    $c >= $count
                    && $num < $max
                ){
                    $this->response->result[] = ['message'=>$__sm['message'], 'solution'=>(isset($__sm['msg']) ? $__sm['msg'] : '')];
                    $num++;
                }
            }
        }

        return $this->response;
    }
}