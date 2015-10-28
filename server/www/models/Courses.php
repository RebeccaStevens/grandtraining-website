<?php  namespace GrandTraining\www\models;

use GrandTraining\www\bases\BaseModel;

class Courses extends BaseModel {

	function __construct(){
		parent::__construct();
	}

	public function getCourses(){
		$db = $this->_dbconnect();
		$result = $db
			->select()
			->from('course')
			->orderBy('displayorder')
			->execute();

		return $result;
	}

}
