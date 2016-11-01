<?php

namespace lib\model;

class StringField extends Field{
    public function getType() {
        return 'varchar(100)';
    }
} 