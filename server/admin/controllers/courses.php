<?php  namespace GrandTraining\admin\controllers;

use GrandTraining\www\bases\BaseController;

class courses extends BaseController {

  function __construct(){
    parent::__construct();
  }

  /**
	 * URL: /courses
	 */
  public function index($data) {
    $this->_requireLoggedIn();
    echo 'courses controller';
  }
}
