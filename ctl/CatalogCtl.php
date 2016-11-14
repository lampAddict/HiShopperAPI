<?php

namespace ctl;

use core\JsonRequest;


/**
 * Class CatalogCtl
 * @package ctl
 */
class CatalogCtl extends Ctl{

    use \_traits\Auth;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {
        parent::__construct($request);
    }
}