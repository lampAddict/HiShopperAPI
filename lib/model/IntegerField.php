<?php

namespace lib\model;

class IntegerField extends Field{

    public function getType() {
        return 'int(11)';
    }

    public function getRaw() {
        return intval(parent::getRaw());
    }

    public function get() {
        return parent::get() === null ? null : intval(parent::get());
    }
} 