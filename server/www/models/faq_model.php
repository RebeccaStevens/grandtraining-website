<?php
require FILE_BASE_MODEL;

class FAQ_Model extends Base_Model {

  function __construct(){
    parent::__construct();
  }

  public function getFAQs(){
    $db = $this->_dbconnect();
    $result = $db
      ->select()
      ->from('faq')
      ->orderBy('displayorder')
      ->execute();

    return $result;
  }

}
