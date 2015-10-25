<?php
require FILE_BASE_MODEL;

class Courses_Model extends Base_Model {

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
