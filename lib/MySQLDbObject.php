<?php

namespace lib;

use lib\model\Field;
use lib\model\Object;
use lib\model\ObjectCollection;

/**
 * Class MySQLDbObject
 * @package lib
 * @property Field[] $fields
 */
class MySQLDbObject extends Object {

    /**
     * @var string
     */
    protected $sql;

    /**
     * @return string
     */
    public function getSQL() {
        return $this->sql;
    }

    /**
     * @var string
     */
    protected $table;

    /**
     * @var MySQLDbEngine
     */
    protected $engine;

    protected $collectionClass;

    /**
     * @return \lib\MySQLDbEngine
     */
    public function getEngine() {
        return $this->engine;
    }

    public function setEngine($engine) {
        $this->engine = $engine;
    }

    /**
     * @param MySQLDbEngine $engine Database engine object for data manipulation
     * @param $table
     * @param $collectionClass
     */
    function __construct(MySQLDbEngine $engine, $table, $collectionClass = 'lib\model\ObjectCollection') {
        $this->engine   = $engine;
        $this->table     = $table;
        $this->collectionClass  = $collectionClass;
    }

    /**
     *
     */
    protected function clear() {
        foreach ($this->fields as $field)
            $field->clear();
    }

    /**
     * @return ObjectCollection
     */
    public function query() {
        $class = $this->collectionClass;
        $collection = new $class();
        $rarr       = $this->engine->fetch_all_array( $this->sql );
        foreach ($rarr as $row) {
            $obj = clone $this;
            $obj->fromRawArray($row);
            $collection->add($obj);
        }
        return $collection;
    }

    public function setField($name, Field $field) {
        if ($field instanceof \lib\model\Field)
            parent::setField($name, $field);
        else throw new \Exception("MySQLDbObject accepts only MySQLDb Field type");
    }

    public function createTable() {
        $this->engine->createTable($this->table, $this->fields);
    }

}