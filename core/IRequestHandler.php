<?php

namespace core;

interface IRequestHandler {
    /**
     * @return bool
     */
    public function handle();
} 