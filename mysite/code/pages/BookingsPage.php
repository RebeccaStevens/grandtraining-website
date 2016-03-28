<?php

class BookingsPage extends Page {

}

class BookingsPage_Controller extends Page_Controller {

	private static $allowed_actions = array(
		'add',
		'AddStudentForm',
		'attendees'
	);

	public function AttendeesURL() {
		return Director::get_current_page()->URLSegment . '/attendees/' . $this->request->param('ID');
	}

	public function add(SS_HTTPRequest $request) {
		$scheduledCourseID = $request->param('ID');
		if ($scheduledCourseID === null) {
			$this->httpError(404);
			return array();
		}

		$scheduledCourse = ScheduledCourse::get()->byID($scheduledCourseID);
		if ($scheduledCourse === null) {
			$this->httpError(404);
			return array();
		}

		$getData = $request->getVar('getData');
		if ($getData) {
			$course = Course::get()->byID($scheduledCourse->CourseID);

			return $this->sendJSON(array(
				'courseTitle' => $course->Title,
				'dateRange' => $scheduledCourse->StartDay . ' - ' . $scheduledCourse->EndDay,
				'price' => '$' . $scheduledCourse->Price,
			));
		}

		return $this->__render($request, array(
            'ScheduledCourse' => $scheduledCourse
        ));
	}

	public function AddStudentForm() {

        // Create fields
        $fields = new FieldList(
			HiddenField::create('SCID', 'SCID', $this->request->param('ID')),
			TextField::create('FirstName', 'First Name'),
			TextField::create('Surname', 'Surname'),
			NumericField::create('Age', 'Age'),
			DropdownField::create('Gender')
				->setSource(singleton('Student')->dbObject('Gender')->enumValues())
        );

        // Create actions
        $actions = new FieldList(
            new FormAction('addStudent', 'Submit')
        );

		$required = new RequiredFields(
			'StudentFirstName',
			'StudentSurname',
			'StudentAge'
		);

        return new Form($this, __FUNCTION__, $fields, $actions, $required);
    }

	public function addStudent($data, $form) {
		$attendee = array(
			'FirstName' => $data['FirstName'],
			'Surname' => $data['Surname'],
			'Age' => $data['Age'],
			'Gender' => $data['Gender']
		);

		$scid = $data['SCID'];
		$attendees = $this->getAttendees($scid);
		$attendees[] = $attendee;

		$allAttendees = Session::get('attendees');
		$allAttendees[$scid] = $attendees;
		Session::set('attendees', $allAttendees);
		Session::save();

		// Debug::show($student);
		// die();

	    // $form->sessionMessage('Message', 'good');

	    return $this->redirectBack();
	}

	private function getAttendees($id) {
		$attendeesBySC = Session::get('attendees');
		if ($attendeesBySC === null) {
			return array();
		}
		if (!isset($attendeesBySC[$id])) {
			return array();
		}
		return $attendeesBySC[$id];
	}

	private function hasAttendees($id) {
		$attendeesBySC = Session::get('attendees');
		if ($attendeesBySC === null || count($attendeesBySC) === 0) {
			return false;
		}
		$attendees = $attendeesBySC[$id];
		if ($attendees === null || count($attendees) === 0) {
			return false;
		}
		return true;
	}

	public function attendees(SS_HTTPRequest $request) {
		$scid = $request->param('ID');
		$attendees = $this->getAttendees($scid);
		return $this->sendJSON($attendees);
	}

}
