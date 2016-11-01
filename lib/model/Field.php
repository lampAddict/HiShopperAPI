<?php

namespace lib\model;

class Field {

    /**
     * @var
     */
    protected $raw;

    /**
     * @var
     */
    private $name;

    /**
     * @param $name
     * @internal param bool $isPrimary
     */
    function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRaw() {
        return $this->raw;
    }

    /**
     * @param $data
     */
    public function setRaw($data) { $this->raw = $data; }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function get() { return $this->raw; }

    /**
     * @param $value
     */
    public function set($value) { $this->raw = $value; }

    public function clear() { $this->raw = null;}
} 