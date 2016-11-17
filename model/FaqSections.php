<?php

namespace model;

use core\Dispatcher;

use lib\MySQLDbObject;
use lib\model\IntegerField;
use lib\model\StringField;

/**
 * Class FaqSections
 * @package model
 */
class FaqSections extends MySQLDbObject{

    protected $code;

    function __construct() {
        parent::__construct(Dispatcher::obj()->getConfig()->engine, 'faq_sections');
        $this->setField('id', new IntegerField('id'));
        $this->setField('name', new StringField('name'));

        //грязный хак для join запроса
        $this->setField('question', new StringField('question'));
        $this->setField('answer', new StringField('answer'));
    }

    /**
     * Get F.A.Q content including sections and corresponding pairs question-answer
     *
     * @return \lib\model\ObjectCollection
     */
    public function getFaqContent(){
        $this->sql = "SELECT fs.name, fi.question, fi.answer FROM `".$this->table."` as fs LEFT JOIN `faq_items` as fi ON fs.id = fi.sid ORDER BY fs.id DESC;";
        return $this->query();
    }
}