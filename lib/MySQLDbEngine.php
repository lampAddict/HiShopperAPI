<?php

namespace lib;

class MySQLDbEngine {

private $_server     = '';
private $_user       = '';
private $_pass       = '';
private $_database   = '';
private $_pre         = '';

private $affected_rows = 0;

private $link_id;
private $query_id;

/**
 * @param        $server
 * @param        $user
 * @param        $pass
 * @param        $database
 * @param string $pre
 */
function __construct($server, $user, $pass, $database, $pre=''){
	$this->_server = $server;
	$this->_user = $user;
	$this->_pass = $pass;
	$this->_database = $database;
	$this->_pre = $pre;

    $this->connect();
}

/**
 *
 */
private function connect() {

	$this->link_id = mysqli_connect($this->_server, $this->_user, $this->_pass);

	if ( !$this->link_id ) {
		$this->show_error_msg("Could not connect to server: <b>$this->server</b>.");
	}

	mysqli_set_charset($this->link_id, "utf8");

	if( !mysqli_select_db($this->link_id, $this->_database) ) {
		$this->show_error_msg("Could not open database: <b>$this->database</b>.");
	}
}

/**
 * @param $sql
 *
 * @return int|resource
 */
public function query($sql) {

	$this->query_id = mysqli_query($this->link_id, $sql);

	if ( !$this->query_id ) {
		$this->show_error_msg("<b>MySQL Query fail:</b> $sql");
		return 0;
	}
	
	$this->affected_rows = mysqli_affected_rows($this->link_id);
	return $this->query_id;
}

/**
 * @param $query_id
 *
 * @return array
 */
public function fetch_array($query_id) {
	$record = [];
	if ( $query_id instanceof \mysqli_result) {
		$this->query_id = $query_id;
		$record = mysqli_fetch_assoc($query_id);
	}
	
	return $record;
}

/**
 * @param $sql
 *
 * @return array
 */
public function fetch_all_array($sql) {
    $out = array();
	$query_id = $this->query($sql);
	while ( $row = $this->fetch_array($query_id) ){
		$out[] = $row;
	}
	return $out;
}

/**
 * @param $tableName
 * @param $fields
 *
 * @return int
 */
public function createTable($tableName, $fields){

    $fsql = '';
    $index = '';
    foreach( $fields as $fname=>$field ){
        $fsql .= '`'.$fname.'` ' . $field->getType() . ' NOT NULL ' . ($fname == 'id' ? 'AUTO_INCREMENT' : '') . ',';
        if( $fname == 'id' )
            $index = 'INDEX `ind` (`id`)';
    }
    $fsql .= $index;
    $fsql = rtrim($fsql, ',');

    $fsql = '(' . $fsql . ') ENGINE=MyISAM COLLATE="utf8_general_ci";';

    $this->query('CREATE TABLE IF NOT EXISTS ' . $tableName . ' ' . $fsql);
    return $this->query_id;
}

/**
 * @param string $msg
 */
protected function show_error_msg($msg = ''){
    error_log($msg . ' ' .mysqli_error($this->link_id));
}

	public function lastInsertedId(){
		return mysqli_insert_id($this->link_id);
	}
}
