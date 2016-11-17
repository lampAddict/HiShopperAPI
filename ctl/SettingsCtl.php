<?php

namespace ctl;

use core\JsonRequest;

use model\Settings;

/**
 * Class SettingsCtl
 * @package ctl
 */
class SettingsCtl extends Ctl{

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }

    public function settings(){
        $s = new Settings();
        $_s = $s->getSettings()->toArray();
        $this->response->result = ["margin"=>15,"check_price"=>850,"check_free_point"=>15000,"stop_words"=>"блеать хуй"];
        if( !empty($_s) ){
            foreach ($_s as $__s)
                $this->response->result[$__s['name']] = $__s['value'];
        }

        return $this->response;
    }
}