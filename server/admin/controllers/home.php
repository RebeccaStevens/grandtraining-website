<?php  namespace GrandTraining\admin\controllers;

use GrandTraining\www\bases\BaseController;

class home extends BaseController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * URL: /
	 */
	public function index($data) {
		echo 'home controller';
	}
}
