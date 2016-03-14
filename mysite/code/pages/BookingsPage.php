<?php

class BookingsPage extends Page {

}

class BookingsPage_Controller extends Page_Controller {

	private static $allowed_actions = array(
		'add',
		'AddStudentForm'
	);

	public function add(SS_HTTPRequest $request) {
		$scheduledCourseID = $request->getVar('scid');
		if ($scheduledCourseID === null) {
			$this->httpError(404);
			return array();
		}

		$scheduledCourse = ScheduledCourse::get()->filter(array('ID' => $scheduledCourseID))[0];
		if ($scheduledCourse === null) {
			$this->httpError(404);
			return array();
		}

		return array(
            'ScheduledCourse' => $scheduledCourse
        );
	}

	public function AddStudentForm() {

        // Create fields
        $fields = new FieldList(
            new TextField('FirstName', 'First Name'),
			new TextField('Surname', 'Surname'),
			new NumericField('Age', 'Age')
        );

        // Create actions
        $actions = new FieldList(
            new FormAction('addBooking', 'Submit')
        );

		$required = new RequiredFields(
			'StudentFirstName',
			'StudentSurname',
			'StudentAge'
		);

        return new Form($this, __FUNCTION__, $fields, $actions, $required);
    }

	public function addStudent($data, $form) {
		$student = Student::create();
		$form->saveInto($student);
	    // $student->Family =

		Session::add_to_array('students', $student);

		// Debug::show($student);
		// die();

	    // $form->sessionMessage('Message', 'good');

	    return $this->redirectBack();
	}

}