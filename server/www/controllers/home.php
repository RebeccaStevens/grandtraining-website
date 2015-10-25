<?php
require FILE_BASE_CONTROLLER;

use lib\Util;
use lib\PageNotFoundException;

/**
 * This is the site's home page's controller.
 */
class Home extends Base_Controller {

	function __construct(){
		parent::__construct();
	}

	/** {@inheritdoc} */
	public function index($data){
		if(Util::arrayHasData($data)){
			$r_data = array_slice($data, 1);
			switch ($data[0]){
				default: throw new PageNotFoundException(); break;
				case 'test.html': $this->_test_dot_html($r_data); break;
			}
			return;
		}
		$this->_indexPage();
	}

	private function _test_dot_html($data){

	}

	//////////////////////////////////////////////////
	// Pages                                        //
	//////////////////////////////////////////////////

	private function _indexPage(){
		$model = $this->_loadModel('main_model');

		$data = array();
		$meta = array(
				'title' => 'Grand Training'
		);

		$metaTags = array(
			'keywords' => 'school holiday, computer, course, class, children, kids',
			'description' => 'A full range of computer courses from introduction to advanced for ages 5 to 17.'
		);

		$this->_renderPage('home.php', $data, $meta, null, null, $metaTags);

// 		$detector = new Mobile_Detect();
// 		if($detector->isTablet()){			$this->_indexTablet();	}
// 		else if($detector->isMobile()){		$this->_indexMobile();	}
// 		else{								$this->_indexDesktop();	}
	}
}
