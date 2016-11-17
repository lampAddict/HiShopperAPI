<?php

namespace ctl;

use core\JsonRequest;

use model\Settings;

/**
 * Class AdCtl
 * @package ctl
 */
class AdCtl extends Ctl{

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }

    public function addAd(){
        
        return $this->response;
    }

    public function updateAd(){

        return $this->response;
    }

    public function deleteAd(){

        return $this->response;
    }

    public function listAd(){

        return $this->response;
    }
}