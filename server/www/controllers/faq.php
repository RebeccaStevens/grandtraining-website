<?php  namespace GrandTraining\www\controllers;

use AirBase\Util;
use AirBase\PageNotFoundException;

use GrandTraining\www\bases\BaseController;
use GrandTraining\www\models\FAQ as Model;

class faq extends BaseController {

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
		$model = new Model();

		$data = array(
				'faqs' => $model->getFAQs()
		);
		$meta = array(
				'title' => 'FAQ - Grand Training',
		);

		$this->_renderPage('faq.php', $data, $meta);
	}
}
