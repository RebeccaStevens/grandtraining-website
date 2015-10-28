<?php  namespace GrandTraining\www\controllers;

use AirBase\Util;
use AirBase\PageNotFoundException;

use GrandTraining\www\bases\BaseController;
use GrandTraining\www\models\Main as Model;

/**
 * This is the site's home page's controller.
 */
class home extends BaseController {

	function __construct() {
		parent::__construct();
	}

	/** {@inheritdoc} */
	public function index($data) {
		if(Util::arrayHasData($data)) {
			$r_data = array_slice($data, 1);
			switch ($data[0]) {
				default: throw new PageNotFoundException(); break;
				case 'test.html': $this->_test_dot_html($r_data); break;
			}
			return;
		}
		$this->_indexPage();
	}

	private function _test_dot_html($data) {

	}

	//////////////////////////////////////////////////
	// Pages                                        //
	//////////////////////////////////////////////////

	private function _indexPage() {
		$model = new Model();

		$data = array();
		$meta = array(
				'title' => 'Grand Training'
		);

		$metaTags = array(
			'keywords' => 'school holiday, computer, course, class, children, kids',
			'description' => 'A full range of computer courses from introduction to advanced for ages 5 to 17.'
		);

		$this->_renderPage('home.php', $data, $meta, null, null, $metaTags);
	}
}
