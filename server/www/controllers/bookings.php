<?php
require FILE_BASE_CONTROLLER;

use lib\Session;
use lib\Util;
use lib\PageNotFoundException;
use lib\form\Form;
use lib\form\fieldvalidationrule\FieldValidationRule;
use lib\form\fieldvalidationrule\MaxLength;
use lib\form\fieldvalidationrule\MinLength;
use lib\form\fieldvalidationrule\MinValue;
use lib\form\fieldvalidationrule\MaxValue;
use lib\form\fieldvalidationrule\IsOneOf;
use lib\form\fieldvalidationrule\IsInteger;
use lib\form\fieldvalidationrule\IsPositive;

class Bookings extends Base_Controller {

	function __construct(){
		parent::__construct();
	}

	/**
	 * URL: /bookings
	 */
	public function index($data){
		if(Util::arrayHasData($data)){
			$r_data = array_slice($data, 1);
			switch ($data[0]){
				default: throw new PageNotFoundException(); break;
				case 'dates.json': $this->_dates_dot_json($r_data); break;
			}
			return;
		}

		$model = $this->_loadModel('bookings_model');

		$data = array(
				'attendees' => $model->getAllAttendeesGroupByStartDate()
		);
		$meta = array(
				'title' => 'Bookings - Grand Training'
		);

		$this->_renderPage('bookings.php', $data, $meta);
	}

	/**
	 * URL: /bookings/bookcourse
	 */
	public function bookcourse($data){
		if(Util::arrayHasData($data)){
			if(count($data) != 1){
				throw new PageNotFoundException();
			}
			switch($data[0]){
				default: throw new PageNotFoundException(); break;
				case 'add':
					$this->_addAttendee();
					break;
				case 'remove':
					$this->_removeAttendee();
					break;
				case 'nextattendeeid':
					$this->_nextAttendeeId();
					break;
			}
			return;
		}
		$courseid = $this->_getCourseId();
		if($courseid === false){
			header('location: '.URL.'bookings/');
			exit;
		}
		$model = $this->_loadModel('bookings_model');

		$data = array(
				'courseId' => $courseid,
				'courseTitle' => $model->getCourseTitle($courseid),
				'otherAttendees' => $model->getAttendeesForCourse($courseid)
		);
		$meta = array(
				'title' => 'Book Course - Grand Training'
		);
		$this->_renderPage('bookings/bookcourse.php', $data, $meta);
	}

	/**
	 * URL: /bookings/details
	 */
	public function details($data){
		if(Util::arrayHasData($data)){
			throw new PageNotFoundException();
		}

		$model = $this->_loadModel('bookings_model');

		// make sure there some attends to book
		if($model->getAttendeeCount() <= 0){
			// if not, get out of here
			header('location: '.URL.'bookings/');
			exit;
		}

		$data = array(
		);
		$meta = array(
				'title' => 'Booking Details - Grand Training'
		);
		$this->_renderPage('bookings/details.php', $data, $meta);
	}

	/**
	 * Return's the courseid posted to the page.
	 * If there is no posted courseid, it is pulled from the session data if avaliable,
	 * otherwise false is returned.
	 * @return Ambigous <boolean, integer>
	 */
	private function _getCourseId(){
		$courseid = false;
		if(isset($_POST['course'])){
			$courseid = $_POST['course'];
			Session::set('bookcourse_courseid', $courseid);
		}
		else{
			$courseid = Session::get('bookcourse_courseid');
		}
		return $courseid;
	}

	/**
	 * Add an attendee to the session storage.
	 * Attendee details should be in $_POST
	 * Will then a echo out a json responce to whether or not the attendee was added successfully
	 */
	private function _addAttendee(){
		$model = $this->_loadModel('bookings_model');
		$courseid = $this->_getCourseId();

		$form = new Form();
		$form
			->post('name')
			->validateField(new MinLength(2))
			->validateField(new MaxLength(35))
			->post('age')
			->validateField(new IsInteger())
			->validateField(new MinValue(5))
			->validateField(new MaxValue(17))
			->post('coursedateid')
			->validateField(new IsOneOf($model->getValidCourseDateIds($courseid)));

		if($form->isValid()){
			$form
				->post('days')
				->validateField(new IsInteger())
				->validateField(new MinValue(1))
				->validateField(new MaxValue(bindec('1111111')))
				->validateField(new BitwiseOrEquals($model->getBitwiseDays($form->getData('coursedateid'))));
		}

		$responce = array();
		if($form->isValid() && $model->addAttendee($form->getData('name'), $form->getData('age'), $form->getData('coursedateid'), $form->getData('days'), $courseid)){
			$responce['status'] = 'success';
		}
		else{
			$responce['status'] = 'error';
		}

		header('Content-type: application/json');
		echo json_encode($responce);
	}

	/**
	 * Remove an attendee to the session storage.
	 * Attendee id should be in $_POST
	 * Will then a echo out a json responce to whether or not the attendee was removed
	 */
	private function _removeAttendee(){
		$model = $this->_loadModel('bookings_model');

		$form = new Form();
		$form
			->post('attendeeid')
			->validateField(new IsInteger())
			->validateField(new MinValue(0));

		$responce = array();
		if($form->isValid() && $model->removeAttendee($form->getData('attendeeid'))){
			$responce['status'] = 'success';
		}
		else{
			$responce['status'] = 'error';
		}

		header('Content-type: application/json');
		echo json_encode($responce);
	}

	/**
	 * echo out the next attendee id that will be used on the server in plain text
	 */
	private function _nextAttendeeId(){
		$model = $this->_loadModel('bookings_model');
		header('Content-type: text/plain');
		echo $model->getNextAttendeeId();
	}

	/**
	 * echo out json of the course dates availiable
	 * A courseid can be specified in $_GET to refine the selection
	 * @param array $data Any additional path data passed in the url
	 * @throws PageNotFoundException if $data contains any data
	 */
	private function _dates_dot_json($data){
		if(Util::arrayHasData($data)){
			throw new PageNotFoundException();
		}
		$model = $this->_loadModel('bookings_model');
		$courseid = isset($_GET['course']) ? $_GET['course'] : 0;

		header('Content-type: application/json');
		echo json_encode($model->getCourseDates($courseid));
	}
}

class BitwiseOrEquals extends FieldValidationRule {
	/** @var integer The value to bitwise or with */
	private $_bit;

	/**
	 * @param integer $bit the value to bitwise or with
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($bit, $errorMessage='invalid input'){
		parent::__construct($errorMessage);
		$this->_bit = $bit;
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		return ($data | $this->_bit) == $this->_bit;
	}
}
