<?php

namespace ctl;

use core\JsonRequest;
use model\ProductConditions;
use model\ProductColors;

/**
 * Class CatalogCtl
 * @package ctl
 */
class CatalogCtl extends Ctl{

    use \_traits\Localization;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }
    
    public function condition(){
        $pc = new ProductConditions();

        $this->response->result = $pc->getAllPossibleConditions()->toArray();
        return $this->response;
    }

    public function color(){
        $pc = new ProductColors();
        $colors = $pc->getColors()->toArray();
        $this->response->result = [];
        if( !empty($colors) ){
            foreach ($colors as $color){
                $this->response->result[] = [
                     'id'=>$color['id']
                    ,'hex'=>$color['hex']
                    ,'ru'=>$this->getText($color['name'], 'ru')
                    ,'en'=>$this->getText($color['name'], 'en')
                ];
            }
        }

        return $this->response;
    }
}