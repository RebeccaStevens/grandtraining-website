<?php  namespace GrandTraining\www\models;

use GrandTraining\www\bases\BaseModel;

class Courses extends BaseModel {

	function __construct(){
		parent::__construct();
	}

	/**
	 * Get all of the super courses from the database.
	 *
	 * @return array The data from the Database
	 */
	public function getSuperCourses() {
		$db = $this->_dbconnect();
		$result = $db
			->select('id', 'title', 'summery')
			->from('supercourse')
			->orderBy('displayorder')
			->execute();

		return $result;
	}

	/**
	 * Get all of the courses from the database.
	 *
	 * @param string $supercourse (optional) Restrict result to this super course
	 * @param string $coursetype (optional) Restrict result to this course type
	 * @return array The data from the Database
	 */
	public function getCourses($supercourse=null, $coursetype=null) {
		$db = $this->_dbconnect();
		$query = $db
			->select('id', 'title', 'shortdescription', 'description', 'minage', 'maxage', 'price')
			->from('course')
			->orderBy('displayorder');

		$bindData = array();

		if (isset($supercourse)) {
			$query->where('supercourse = :super');
			$bindData[':super'] = $supercourse;
		}
		else {
			$query->field('supercourse');
		}

		if (isset($coursetype)) {
			$query->where('type = :type');
			$bindData[':type'] = $coursetype;
		}
		else {
			$query->field('type');
		}

		return $query->execute($bindData);
	}

	/**
	 * Check whether a super course exists.
	 *
	 * @param string $supercourse The super course to check
	 * @return boolean
	 */
	public function superCoursesExist($supercourse) {
		$db = $this->_dbconnect();
		$query = $db
			->select('id')
			->from('supercourse')
			->where('supercourse.id = :super');

		$result = $query->execute(array(
			':super' => $supercourse
		));

		return count($result) > 0;
	}

	/**
	 * Check whether a course type exist.
	 *
	 * @param string $type The course type to check
	 * @return boolean
	 */
	public function coursesTypeExist($type) {
		$db = $this->_dbconnect();
		$sql =
			'SELECT coursetype.enumlabel FROM (
			  SELECT enumlabel
			    FROM pg_enum
			    WHERE enumtypid = \'coursetype\'::regtype
				)
				AS "coursetype"
				WHERE "coursetype"."enumlabel" = :type';

		$result = $db->execute($sql, array(
			':type' => $type
		));

		return count($result) > 0;
	}
}
