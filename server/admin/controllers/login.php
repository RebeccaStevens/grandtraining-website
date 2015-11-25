<?php  namespace GrandTraining\admin\controllers;

use GrandTraining\www\bases\BaseController;
use GrandTraining\admin\models\Login as Model;

class login extends BaseController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * URL: /login
	 */
	public function index($data) {
		$model = new Model();
		if ($model::isLoggedIn()) {
			echo 'logged in';
		}
		else {
			$url = $model->getAuthUrl();
			echo "<a href=\"$url\">Click Here to log in</a>";
		}
	}

	/**
	 * URL: /login/logout
	 */
	public function logout($data) {
		Model::logout();
		header('location: '.URL);
	  exit;
	}

	/**
	 * URL: /login/oauth2
	 *
	 * This is the route the google will send the resonce code to.
	 */
	public function oauth2($data) {
		$model = new Model();
		$model->checkRedirectCode();		// if exception is thrown, it caught at the top level of app

		header('location: '.URL);
		exit;
	}
}
