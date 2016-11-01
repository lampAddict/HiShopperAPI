<?php

namespace core;

/**
 * Class JsonRequest
 * @package core
 */
class JsonRequest {

    /**
     * @var
     */
    public $requestName;

    /**
     * @var
     */
    public $data;

    /**
     * @param $json
     */
    function __construct($json = null) {
        
        if ($json){
            $arr = json_decode($json, true);
            foreach($arr as $k => $v) $this->$k = $v;
        }
        if (!$this->data) $this->data = new \ArrayObject();
    }

    /**
     * @return JsonResponse
     */
    public function response() {
        return new JsonResponse($this);
    }

    function __toString() {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
} 