<?php

namespace core;

class JsonResponse {

    const UNKNOWN_ACTION            = 0x0001;

    public $errors = [];
    public $result = null;

    function __construct(JsonRequest $request) {
        //$this->result = new \ArrayObject();
    }

    function __toString() {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

} 