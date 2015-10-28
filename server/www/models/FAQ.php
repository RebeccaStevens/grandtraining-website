<?php  namespace GrandTraining\www\models;

use GrandTraining\www\bases\BaseModel;

class FAQ extends BaseModel {

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
