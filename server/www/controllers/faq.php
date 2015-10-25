<?php
require FILE_BASE_CONTROLLER;

use lib\Util;
use lib\PageNotFoundException;

class FAQ extends Base_Controller {

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
		$model = $this->_loadModel('faq_model');

		$data = array(
				'faqs' => $model->getFAQs()
		);
		$meta = array(
				'title' => 'FAQ - Grand Training',
		);

		$this->_renderPage('faq.php', $data, $meta);
	}
}
