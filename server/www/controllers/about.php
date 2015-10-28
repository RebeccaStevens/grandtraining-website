<?php namespace GrandTraining\www\controllers;

use AirBase\Util;
use AirBase\PageNotFoundException;

use GrandTraining\www\bases\BaseController;
use GrandTraining\www\models\Main as Model;

class about extends BaseController {

  function __construct(){
    parent::__construct();
  }

  /** {@inheritdoc} */
  public function index($data){
    if(Util::arrayHasData($data)){
      throw new PageNotFoundException();
    }

    $this->_indexPage();
  }

  //////////////////////////////////////////////////
  // Pages                                        //
  //////////////////////////////////////////////////

  private function _indexPage(){
    $model = new Model();

    $data = array();
    $meta = array(
        'title' => 'About - Grand Training'
    );

    $this->_renderPage('about.php', $data, $meta);
  }

  public function contact($data){
    if(Util::arrayHasData($data)){
      throw new PageNotFoundException();
    }

    $data = array();
    $meta = array(
        'title' => 'Contact Us - Grand Training'
    );

    $this->_renderPage('about/contact.php', $data, $meta);
  }
}
