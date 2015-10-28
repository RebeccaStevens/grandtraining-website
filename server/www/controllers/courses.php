<?php  namespace GrandTraining\www\controllers;

use AirBase\Util;
use AirBase\PageNotFoundException;

use GrandTraining\www\bases\BaseController;
use GrandTraining\www\models\Courses as Model;

class courses extends BaseController {

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
    $courses_model = new Model();

    $data = array(
        'courses' => $courses_model->getCourses()
    );
    $meta = array(
        'title' => 'Courses - Grand Training',
    );

    $this->_renderPage('courses.php', $data, $meta);
  }
}
