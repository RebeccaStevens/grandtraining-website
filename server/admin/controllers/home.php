<?php  namespace GrandTraining\admin\controllers;

use GrandTraining\www\bases\BaseController;
use GrandTraining\admin\models\Login as LoginModel;

class home extends BaseController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * URL: /
	 */
	public function index($data) {
		$model = new LoginModel();
		if (!$model->isLoggedIn()) {
			header('location: '.URL_LOGIN);
		  exit;
		}
	}
}
