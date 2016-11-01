<?php

namespace lib\model;

abstract class Object implements \ArrayAccess {

    /**
     * @var Field[]
     */
    protected $fields;

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset) {
        return $this->$offset;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset) {
    }

    /**
     * @param $name
     * @return mixed
     */
    function __get($name) {
        return $this->fields[$name]->get();
    }

    /**
     * @param $name
     * @param $value
     */
    function __set($name, $value) {
        $this->fields[$name]->set($value);
    }

    /**
     * @param $name
     * @return bool
     */
    function __isset($name) {
        return isset($this->fields[$name]);
    }

    /**
     * @param $name
     * @param Field $field
     */
    public function setField($name, Field $field) {
        $this->fields[$name] = $field;
    }

    /**
     * @param $arr
     */
    protected function fromRawArray($arr) {
        foreach ($arr as $key => $value)
            foreach ($this->fields as $field)
                if ($field->getName() == $key) $field->setRaw($value);
    }

    /**
     * @return array
     */
    protected function toRawArray() {
        $ret = array();
        foreach ($this->fields as $field)
            $ret[$field->getName()] = $field->getRaw();
        return $ret;
    }

    public function fromArray($arr) {
        foreach ($arr as $key => $value)
            if (isset($this->$key)) $this->$key = $value;
    }

    public function toArray() {
        $ret = array();
        foreach ($this->fields as $key => $field)
            if ($field->get() !== null) $ret[$key] = $field->get();
        return $ret;
    }

    function __clone() {
        $newFields = array();
        foreach($this->fields as $key => $field){
            $newFields[$key] = clone $field;
        }
        $this->fields = $newFields;
    }
}