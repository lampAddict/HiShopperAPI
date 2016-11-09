<?php

namespace ctl;

use core\JsonRequest;
use core\JsonResponse;

/**
 * Class Ctl
 * @package ctl
 */
class Ctl {

    /**
     * @var JsonRequest
     */
    public $request;

    /**
     * @var JsonResponse
     */
    public $response;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {

        $this->request = $request;
        $this->response = $this->request->response();
    }
}