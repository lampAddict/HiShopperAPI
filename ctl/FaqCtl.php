<?php

namespace ctl;

use core\JsonRequest;

use model\FaqSections;

/**
 * Class FaqCtl
 * @package ctl
 */
class FaqCtl extends Ctl{

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }

    /**
     * Returns frequently asked questions ina form of associative array where keys are sections and values are arrays of questions composed with appropriate answers
     * 
     * @return \core\JsonResponse
     */
    public function faq(){
        $fs = new FaqSections();
        $_fs = $fs->getFaqContent()->toArray();
        if( !empty($_fs) ){
            $this->response->result = [];
            foreach ($_fs as $__fs){
                $this->response->result[$__fs['name']][] = ['question'=>$__fs['question'], 'answer'=>$__fs['answer']];
            }

            return $this->response;
        }
    }
}