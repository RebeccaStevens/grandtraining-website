<?php  namespace GrandTraining\admin\controllers;

use AirBase\AirBase;

use GrandTraining\www\controllers\courses as BaseController;

class courses extends BaseController {

  function __construct(){
    parent::__construct();
  }

  /**
	 * URL: /courses
	 */
  public function index($data) {
    // $this->_requireLoggedIn();
    parent::index($data);
  }
}
