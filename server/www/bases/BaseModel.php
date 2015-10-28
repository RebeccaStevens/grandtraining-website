<?php namespace GrandTraining\www\bases;

use AirBase\Model;
use AirBase\database\Database;

class BaseModel extends Model {

  private $_db;

  function __construct(){
    parent::__construct();
  }

  /**
   * Connect to the database.
   *
   * @return Database the database object
   */
  protected function _dbconnect(){
    if(!isset($this->_db)){
      $this->_db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);      // construct a database object
      $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);    // set the default fetch mode to associative
    }
    return $this->_db;
  }
}
