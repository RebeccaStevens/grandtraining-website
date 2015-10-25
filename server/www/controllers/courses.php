<?php
require FILE_BASE_CONTROLLER;

use lib\Util;
use lib\PageNotFoundException;

class Courses extends Base_Controller {

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
    $courses_model = $this->_loadModel('courses_model');

    $data = array(
        'courses' => $courses_model->getCourses()
    );
    $meta = array(
        'title' => 'Courses - Grand Training',
    );

    $this->_renderPage('courses.php', $data, $meta);
  }
}
