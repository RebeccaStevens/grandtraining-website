<?php
require FILE_BASE_CONTROLLER;

use lib\Util;
use lib\PageNotFoundException;

class About extends Base_Controller {

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
    $model = $this->_loadModel('main_model');

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
