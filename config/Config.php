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
                ,'user_unfollow' => '\ctl\UserCtl::unfollow'

                ,'user_favoriteAdd' => '\ctl\UserCtl::favoriteAdd'
                ,'user_favoriteRemove' => '\ctl\UserCtl::favoriteRemove'
                ,'user_favoriteList' => '\ctl\UserCtl::favoriteList'
                ,'user_favoriteCount' => '\ctl\UserCtl::favoriteCount'
                ,'user_search' => '\ctl\UserCtl::search'
                ,'user_support' => '\ctl\UserCtl::support'
                ,'user_supportchat' => '\ctl\UserCtl::supportchat'

                ,'faq' => '\ctl\FaqCtl::faq'
                ,'options' => '\ctl\OptionsCtl::options'

                ,'catalog_condition' => '\ctl\CatalogCtl::condition'
                ,'catalog_color' => '\ctl\CatalogCtl::color'
                ,'catalog_section' => '\ctl\CatalogCtl::section'
                ,'catalog_size' => '\ctl\CatalogCtl::size'
                ,'catalog_payment' => '\ctl\CatalogCtl::payment'
                ,'catalog_delivery' => '\ctl\CatalogCtl::delivery'
                ,'catalog_gender' => '\ctl\CatalogCtl::gender'
                ,'catalog_brand' => '\ctl\CatalogCtl::brand'
                ,'catalog_addbrand' => '\ctl\CatalogCtl::addBrand'
            ])
        );

        $this->engine = new MySQLDbEngine( 'localhost', 'root', '', Config::DBNAME, $pre='' );
    }
} 