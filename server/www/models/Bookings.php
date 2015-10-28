<?php  namespace GrandTraining\www\models;

use AirBase\Session;
use GrandTraining\www\bases\BaseModel;

class Bookings extends BaseModel {

	function __construct(){
		parent::__construct();
	}

	/**
	 * Get the title of the course with the given course id
	 * @param integer $courseid The id of the course
	 * @return string The course title
	 */
	public function getCourseTitle($courseid){
		$db = $this->_dbconnect();

		$results = $db->select('course.title')
			->from('course')
			->where('course.id = :courseid')
			->execute(array(':courseid' => $courseid));

		return $results[0]['title'];
	}

	/**
	 * Get all the dates for a given course.
	 * If no course given, dates for all courses will be returned.
	 * If no venue given, dates for all venues will be returned.
	 * @param integer $courseid Id of the course to get the dates for (if not possitive, assumes no courseid was given)
	 * @param string $venue The venue to get dates from (if emptystring, assume no $venue given)
	 * @return array The course's dates
	 */
	public function getCourseDates($courseid=0, $venue=''){
		$db = $this->_dbconnect();

		if($courseid <= 0){
			return $db
				->select('course.id AS courseid', 'coursedate.id AS coursedateid', 'coursedate.venue', 'coursedate.startdate', 'coursedate.days::integer')
				->from('course', 'coursedate')
				->where('course.id = coursedate.course')
				->orderby('coursedate.startdate', 'coursedate.days')
				->orderbyOrder(true, false)
				->execute();
		}

		if(empty($venue)){
			return $db
				->select('coursedate.id AS coursedateid', 'coursedate.venue', 'coursedate.startdate', 'coursedate.days::integer')
				->from('course', 'coursedate')
				->where('coursedate.course = :courseid')
				->where('course.id = coursedate.course')
				->orderby('coursedate.startdate', 'coursedate.days')
				->orderbyOrder(true, false)
				->execute(array(':courseid' => $courseid));
		}

		return $db
			->select('coursedate.id AS coursedateid', 'coursedate.startdate', 'coursedate.days::integer')
			->from('course', 'coursedate')
			->where('coursedate.course = :courseid')
			->where('coursedate.venue = :venue')
			->where('course.id = coursedate.course')
			->orderby('coursedate.startdate', 'coursedate.days')
			->orderbyOrder(true, false)
			->execute(array(':courseid' => $courseid, ':venue' => $venue));
	}

	/**
	 * Get an array of all the course date ids for the given course
	 * @param integer $courseid The id of course to get the course dates for
	 * @return array of positive integers
	 */
	public function getValidCourseDateIds($courseid){
		$db = $this->_dbconnect();

		$results = $db
			->select('coursedate.id')
			->from('coursedate')
			->where('coursedate.course = :courseid')
			->execute(array(':courseid' => $courseid));

		$ids = array();
		foreach($results as $r){
			$ids[] = $r['id'];
		}
		return $ids;
	}

	/**
	 * Get the days a course date offers as a bitwise integer.
	 * How to get what days are offered from the return value:
	 * (result & (1<<6)) > 0 <=> course date offered on Monday
	 * (result & (1<<5)) > 0 <=> course date offered on Tueday
	 * etc.
	 * @param integer $coursedateid The id of the coursedate to get
	 * @return integer min:0 max:0b1111111 (127)
	 */
	public function getBitwiseDays($coursedateid){
		$db = $this->_dbconnect();

		$results = $db
			->select('coursedate.days::integer')
			->from('coursedate')
			->where('coursedate.id = :coursedateid')
			->execute(array(':coursedateid' => $coursedateid));

		return $results[0]['days'];
	}

	/**
	 * Get all the attendees saved in session storage of the given course.
	 * Will return an array of date blocks, each date block is an associative array with keys 'date'=>string, 'venue'=>string, 'coursedateid'=>integer, and 'attendees'=>array.
	 * 'attendees' is an array of attendess, each attendee is an array with keys 'name'=>string, 'age'=>iteger, 'mon'=>boolean, 'tue'=>boolean, 'wed'=>boolean, 'thu'=>boolean, 'fri'=>boolean, 'sat'=>boolean, 'sun'=>boolean
	 * @param integer $courseid
	 * @return array
	 */
	public function getAttendeesForCourse($courseid){
		$saved_attendees = $this->_getSavedAttendees();

		if(empty($saved_attendees)){
			return array();
		}

		$dates = array();	// an array of the attendees on the course grouped by coursedateid

		// populate the $dates array
		foreach($saved_attendees as $attendee){
			// get all the attendees that are to be book on this course
			if($courseid == $attendee['courseid']){
				$i = $attendee['coursedateid'];
				if(!array_key_exists($i, $dates)){
					$dates[$i] = array();
				}
				$dates[$i][] = $attendee;
			}
		}

		// if no attendees booked on this course so far
		if(empty($dates)){
			return array();
		}

		// connect to the database and get the data values for the dates to be booked

		$where = '';
		$binddata = array();
		$i = 0;
		// build up the where cause and data bindings
		foreach(array_keys($dates) as $coursedateid){
			$where .= "coursedate.id = :coursedateid$i OR ";
			$binddata[":coursedateid$i"] = $coursedateid;
			$i++;
		}
		$where = rtrim($where, ' OR ');

		// query the database
		$db = $this->_dbconnect();
		$results = $db
			->select('coursedate.id', 'coursedate.course', 'coursedate.venue', 'coursedate.startdate', 'coursedate.days::integer')
			->from('coursedate')
			->where($where)
			->execute($binddata);

		$datesToData = array();	// like the $dates array except the with the actual data

		// populate the $datesToData array with the data recieved from the database
		foreach($results as $r){
			$datesToData[$r['id']] = array(
				'days' => $r['days'],
				'course' => $r['course'],
				'venue' => $r['venue'],
				'startdate' => $r['startdate']
			);
			$dates[$r['id']][0]['startdate'] = $r['startdate'];	// save the startdate value in the first element of each date group - this value is need to sort $dates array below
		}

		// sort the $dates array by startdate (sort equal startdates by days)
		uasort($dates, function($a, $b){
			if ($a[0]['startdate'] == $b[0]['startdate']) {
				if ($a[0]['days'] == $b[0]['days']) {
					return 0;
				}
				return ($a[0]['days'] < $b[0]['days']) ? -1 : 1;
			}
			return ($a[0]['startdate'] < $b[0]['startdate']) ? -1 : 1;
		});

		$attendeesForCourse = array();	// the array to return

		// populate the $attendeesForCourse array
		foreach($dates as $coursedateid => $dateGroup){
			$date = $this->_getDateString($datesToData[$coursedateid]['startdate'],
				($datesToData[$coursedateid]['days'] & (1 << 6)) > 0,
				($datesToData[$coursedateid]['days'] & (1 << 5)) > 0,
				($datesToData[$coursedateid]['days'] & (1 << 4)) > 0,
				($datesToData[$coursedateid]['days'] & (1 << 3)) > 0,
				($datesToData[$coursedateid]['days'] & (1 << 2)) > 0,
				($datesToData[$coursedateid]['days'] & (1 << 1)) > 0,
				($datesToData[$coursedateid]['days'] & 1) > 0);
			$venue = $datesToData[$coursedateid]['venue'];
			$startdate = $datesToData[$coursedateid]['startdate'];
			$attendees = array();
			foreach($dateGroup as $attendee){
				$attendees[] = array(
					'id'   => $attendee['id'],
					'name' => $attendee['name'],
					'age'  => $attendee['age'],
					'mon'  => ($attendee['days'] & (1 << 6)) > 0,
					'tue'  => ($attendee['days'] & (1 << 5)) > 0,
					'wed'  => ($attendee['days'] & (1 << 4)) > 0,
					'thu'  => ($attendee['days'] & (1 << 3)) > 0,
					'fri'  => ($attendee['days'] & (1 << 2)) > 0,
					'sat'  => ($attendee['days'] & (1 << 1)) > 0,
					'sun'  => ($attendee['days'] & 1) > 0
				);
			}

			$attendeesForCourse[] = array(
				'date' => $date,
				'venue' => $venue,
				'coursedateid' => $coursedateid,
				'startdate' => $startdate,
				'attendees' => $attendees
			);
		}

		return $attendeesForCourse;
	}

	/**
	 * Add an attendee to the session
	 * @param string $name The name of the attendee
	 * @param integer $age The age of the attendee
	 * @param integer $coursedateid The coursedateid they are booked on
	 * @param integer $days A bitwise number for the days they are book in for
	 * @param integer $courseid The courseid they are booked on
	 * @return boolean true if after this method is executed, there is no attendee in local storage with the gieven id
	 */
	public function addAttendee($name, $age, $coursedateid, $days, $courseid){
		$savedAttendees = Session::get('booking_attendees');
		if($savedAttendees === false){
			$savedAttendees = array();
		}
		$id = Session::get('booking_attendee_next_id');
		if($id === false){
			$id = 0;
		}
		$savedAttendees[] = array(
			'id'			=> $id++,
			'name'			=> $name,
			'age'			=> $age,
			'days'			=> $days,
			'coursedateid'	=> $coursedateid,
			'courseid'		=> $courseid,
		);
		Session::set('booking_attendees', $savedAttendees);
		Session::set('booking_attendee_next_id', $id);
		return true;
	}

	/**
	 * Remove an attendee from the session
	 * @param integer $attendeeid The id of the attendee to remove
	 * @return boolean true if after this method is executed, there is no attendee in local storage with the gieven id
	 */
	public function removeAttendee($attendeeid){
		$savedAttendees = Session::get('booking_attendees');
		if($savedAttendees === false){
			return true;
		}

		$attendeekey = false;
		foreach($savedAttendees as $key => $savedAttendee){
			if($savedAttendee['id'] == $attendeeid){
				$attendeekey = $key;
				break;
			}
		}

		if($attendeekey === false){
			return true;
		}

		Session::clearIndex('booking_attendees', $attendeekey);
		if(empty(Session::get('booking_attendees'))){
			Session::clear('booking_attendees');
			Session::clear('booking_attendee_next_id');
		}
		return true;
	}

	/**
	 * Get the next attendee id that will be used
	 * @return integer
	 */
	public function getNextAttendeeId(){
		$id = Session::get('booking_attendee_next_id');
		if($id === false){
			$id = 0;
		}
		return $id;
	}

	/**
	 * Get all the attendees.
	 * Returns an array of dateGroups.
	 * each dateGroups is an assosiative array with keys 'date', 'venue' and 'courses'.
	 * 'courses' maps to an array of courses.
	 * each course is an associative array with keys 'attendees' and 'title'
	 * 'attendees' maps to an array of attendees.
	 * each attendee is an array with keys 'id', 'name', 'age', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' and 'sun'
	 * @return array
	 */
	public function getAllAttendeesGroupByStartDate(){
		$saved_attendees = $this->_getSavedAttendees();

		if(empty($saved_attendees)){
			return array();
		}

		// maps all the course ids in the booking attendees to the an array of value about the course
		$courseMap = array();

		// maps all the course date ids in the booking attendees to the an array of value about the course date
		$coursedateMap = array();

		// populate the keys of the two maps
		foreach($saved_attendees as &$attendee){
			$courseMap[$attendee['courseid']] = false;
			$coursedateMap[$attendee['coursedateid']] = false;

			$attendee['mon'] = ($attendee['days'] & (1 << 6)) > 0;
			$attendee['tue'] = ($attendee['days'] & (1 << 5)) > 0;
			$attendee['wed'] = ($attendee['days'] & (1 << 4)) > 0;
			$attendee['thu'] = ($attendee['days'] & (1 << 3)) > 0;
			$attendee['fri'] = ($attendee['days'] & (1 << 2)) > 0;
			$attendee['sat'] = ($attendee['days'] & (1 << 1)) > 0;
			$attendee['sun'] = ($attendee['days'] & 1) > 0;
		}

		// populate the values of the two maps
		$this->_getCourseDetailsFromCourseIds($courseMap);
		$this->_getCourseDateDetailsFromCourseDateIds($coursedateMap);

		// create an array of all the unique start dates but don't merge different venues
		$venuestartdates = array();
		foreach($coursedateMap as $id=>$v){
			// store venues and start dates in the keys to insure they are unique
			if(!array_key_exists($v['venue'], $venuestartdates)){
				$venuestartdates[$v['venue']] = array();
			}
			if(!array_key_exists($v['startdate'], $venuestartdates[$v['venue']])){
				$venuestartdates[$v['venue']][$v['startdate']] = array();
			}
			$attendees = array();
			foreach($saved_attendees as &$attendee){
				if($attendee['coursedateid'] == $id){
					$attendees[] = $attendee;
				}
			}
			$venuestartdates[$v['venue']][$v['startdate']][] = array(
					'attendees' => $attendees,
					'title' => $courseMap[$v['course']]['title']
			);
		}

		$dateGroups = array();
		foreach($venuestartdates as $venue=>$v){
			foreach($v as $startdate=>$courses){
				$dateGroups[] = array(
						'date' => $startdate,
						'venue' => $venue,
						'courses' => $courses
				);
			}
		}

		return $dateGroups;
	}

	public function getAttendeeCount(){
		return count($this->_getSavedAttendees());
	}

	/**
	 * Get the saved attendees
	 * @return array of attendees
	 */
	private function _getSavedAttendees(){
		$saved_attendees = Session::get('booking_attendees');
		if($saved_attendees == false){
			return array();
		}
		$this->_sortAttendees($saved_attendees);
		return $saved_attendees;
	}

	/**
	 * Sort the given array of attendees
	 * @param array $attendees
	 * @return number
	 */
	private function _sortAttendees(array &$attendees){
		uasort($attendees, function($a, $b){
			if ($a['age'] == $b['age']) {
				return 0;
			}
			return ($a['age'] < $b['age']) ? 1 : -1;
		});
	}

	/**
	 * Get an array of course details for each id given as a key in the $courseids array, the id (key) is then mapped to the array of details
	 * @param array $courseids Associative array of ids (ids must be the keys)
	 */
	private function _getCourseDetailsFromCourseIds(array &$courseids){
		$where = '';
		$binddata = array();
		$i = 0;
		foreach(array_keys($courseids) as $id){
			$where .= "course.id = :courseid$i OR ";
			$binddata[":courseid$i"] = $id;
			$i++;
		}
		$where = rtrim($where, ' OR ');

		$db = $this->_dbconnect();
		$results = $db
			->select('course.id', 'course.title')
			->from('course')
			->where($where)
			->execute($binddata);

		foreach($results as $r){
			$courseids[$r['id']] = $r;
		}
	}

	/**
	 * Get an array of course date details for each id given as a key in the $coursedateids array, the id (key) is then mapped to the array of details
	 * @param array $coursedateids Associative array of ids (ids must be the keys)
	 */
	private function _getCourseDateDetailsFromCourseDateIds(array &$coursedateids){
		$where = '';
		$binddata = array();
		$i = 0;
		foreach(array_keys($coursedateids) as $id){
			$where .= "coursedate.id = :coursedate$i OR ";
			$binddata[":coursedate$i"] = $id;
			$i++;
		}
		$where = rtrim($where, ' OR ');

		$db = $this->_dbconnect();
		$results = $db
		->select('coursedate.id', 'coursedate.course', 'coursedate.startdate', 'coursedate.venue')
		->from('coursedate')
		->where($where)
		->execute($binddata);

		foreach($results as $r){
			$coursedateids[$r['id']] = $r;
		}
	}

	/**
	 * Get a Date string from the given starting date (this should be monday)
	 * @param string $date Date string e.g. 2015-01-01
	 * @param boolean $mon Include Monday?
	 * @param boolean $tue Include Tuesday?
	 * @param boolean $wed Include Wednesday?
	 * @param boolean $thu Include Thursday?
	 * @param boolean $fri Include Friday?
	 * @param boolean $sat Include Satday?
	 * @param boolean $sun Include Sunday?
	 * @return string
	 */
	private function _getDateString($date, $mon, $tue, $wed, $thu, $fri, $sat, $sun){
		$startdate = strtotime($date);
		if(!$mon){
			if($tue)		$startdate = strtotime('+1 day', $startdate);
			else if($wed)	$startdate = strtotime('+2 day', $startdate);
			else if($thu)	$startdate = strtotime('+3 day', $startdate);
			else if($fri)	$startdate = strtotime('+4 day', $startdate);
			else if($sat)	$startdate = strtotime('+5 day', $startdate);
			else if($sun)	$startdate = strtotime('+6 day', $startdate);
		}

		$enddate = strtotime($date);
		if($sun)		$enddate = strtotime('+6 day', $enddate);
		else if($sat)	$enddate = strtotime('+5 day', $enddate);
		else if($fri)	$enddate = strtotime('+4 day', $enddate);
		else if($thu)	$enddate = strtotime('+3 day', $enddate);
		else if($wed)	$enddate = strtotime('+2 day', $enddate);
		else if($tue)	$enddate = strtotime('+1 day', $enddate);

		if($startdate == $enddate){
			return date('l', $startdate) . ' ' . date('j', $startdate) . '<sup>'.date('S', $startdate).'</sup> ' . date('F', $startdate) . ' ' . date('Y', $startdate) . ' (1 day course)';
		}
		$days = $mon + $tue + $wed + $thu + $fri + $sat + $sun;
		return date('l', $startdate) . ' ' . date('j', $startdate) . '<sup>'.date('S', $startdate).'</sup> ' . date('F', $startdate) . ' &ndash; ' . date('l', $enddate) . ' ' . date('j', $enddate) . '<sup>'.date('S', $enddate).'</sup> ' . date('F', $enddate) . ' ' . date('Y', $enddate) . ' ('.$days.' day course)';
	}

	public function getDatesBooked(){
		$bookings_attendees = Session::get('bookings_attendees');
		if($bookings_attendees === false){
			return null;
		}

		$dateids = array();
		$courseids = array();
		foreach($bookings_attendees as $attendee){
			$dateids[$attendee['date']] = true;
			$courseids[$attendee['course']] = true;
		}

		$dateidToDateName = $this->_getDateNamesFromIds($dateids);
		$courseidToCourseTitle = $this->_getCourseTitlesFromIds($courseids);

		$dates = array();
		foreach($bookings_attendees as $attendee){
			if(!array_key_exists($dateidToDateName[$attendee['date']], $dates)){
				$dates[$dateidToDateName[$attendee['date']]] = array();
			}
			if(!array_key_exists($courseidToCourseTitle[$attendee['course']], $dates[$dateidToDateName[$attendee['date']]])){
				$dates[$dateidToDateName[$attendee['date']]][$courseidToCourseTitle[$attendee['course']]] = array();
			}
			$dates[$dateidToDateName[$attendee['date']]][$courseidToCourseTitle[$attendee['course']]][] = $attendee;
		}

		foreach($dates as $date => &$courses){
			foreach($courses as $course => &$attendees){
				foreach($attendees as $i => &$attendee){
					$attendee['id'] = array_search($attendee, $bookings_attendees);
					$attendee['day'] = self::daysToArray($attendee['day']);
				}
			}
		}
		return $dates;
	}

	/**
	 * Calculate the cost of single course over x days
	 * @param integer $courseid
	 * @param integer $days
	 * @return number The cost
	 */
	public function calculateSingleCost($courseid, $days){
		$db = $this->_dbconnect();
		$cost = $db->newQuery()
			->fields('course.cost')
			->tables('course')
			->where('course.id = :courseid')
			->select(array(':courseid' => $courseid));

		return $cost[0]['cost'] * $days;
	}

	public function calculateDatesCosts(){
		$bookings_attendees = Session::get('bookings_attendees');
		if($bookings_attendees === false){
			return array();	// no attendees booked
		}

		// split the attendees into groups based on the date they booked
		$dates = array();
		foreach($bookings_attendees as &$attendee){
			if(!array_key_exists($attendee['date'], $dates)){
				$dates[$attendee['date']] = array();
			}
			$dates[$attendee['date']][] = $attendee;
		}

		$datescosts = array();	// to return

		// go through each group (the dates)
		foreach($dates as $date => &$attendees){
			$datescosts[$date] = array('subtotal' => 0, 'discount' => 0, 'total' => 0);	// init this date's cost
			$daycounts = array();
			// go through each group of attendees
			foreach($attendees as $key => &$attendee){
				$daycounts[$key] = self::countDaysBitwise($attendee['day']);
				$datescosts[$date]['subtotal'] += $attendee['cost'];
			}

			// reverse sort for incase days are not equal for all attendees
			rsort($daycounts);

			// add discount for multiple days booked for attendees beound the first
			foreach($daycounts as $key => $days){
				if($key == 0) continue;						// no discount for frist attendee
				if($days >= 5){
					$datescosts[$date]['discount'] += 50;
				}
				else if($days >= 4){
					$datescosts[$date]['discount'] += 40;
				}
				else if($days >= 3){
					$datescosts[$date]['discount'] += 30;
				}
			}
			// calculate and set the total
			$datescosts[$date]['total'] = $datescosts[$date]['subtotal'] - $datescosts[$date]['discount'];
		}

		return $datescosts;
	}

	public function calculateTotalCost(){
		$datecosts = $this->calculateDatesCosts();

		$subtotal = 0;
		$discount = 0;
		foreach($datecosts as $cost){
			$subtotal += $cost['subtotal'];
			$discount += $cost['discount'];
		}

		$total = $subtotal - $discount;

		return array(
				'subtotal' => $subtotal,
				'discount' => $discount,
				'total' => $total
		);
	}

	/**
	 * Convert a bitwise array of days information to an array of booleans.
	 * The array is associative, keyed with the first 2 letters of each day.
	 * @param integer $bitwiseDays The days as an integer
	 * @return array
	 */
	public static function daysToArray($bitwiseDays){
		return array(
				'mo' => ($bitwiseDays & 1<<6) != 0,
				'tu' => ($bitwiseDays & 1<<5) != 0,
				'we' => ($bitwiseDays & 1<<4) != 0,
				'th' => ($bitwiseDays & 1<<3) != 0,
				'fr' => ($bitwiseDays & 1<<2) != 0,
				'sa' => ($bitwiseDays & 1<<1) != 0,
				'su' => ($bitwiseDays & 1) != 0
		);
	}

	/**
	 * Count how many days are true in the array of days.
	 * @param array $days array of days (booleans)
	 * @return integer number of days that are true
	 */
	public static function countDays(array $days){
		$i = 0;
		foreach($days as $value){
			if($value){
				$i++;
			}
		}
		return $i;
	}

	/**
	 * Count how many days are true form a bitwise integer.
	 * @param integer $days bitwise integer of days
	 * @return integer number of days that are true
	 */
	public static function countDaysBitwise($days){
		$i = 0;
		for($j=6; $j>=0; $j--){
			if(($days & 1<<$j) != 0){
				$i++;
			}
		}
		return $i;
	}

	private function _getDateNamesFromIds($ids){
		$where = '';
		$bind = array();
		$i = 0;
		foreach($ids as $k => $v){
			$where .= '( date.id = :dateid'.$i.' ) OR ';
			$bind[':dateid'.$i] = $k;
			$i++;
		}
		$where = rtrim($where, ' OR ');

		$db = $this->_dbconnect();
		$dbresults = $db->newQuery()
			->fields('date.id', 'date.date')
			->tables('date')
			->where($where)
			->select($bind);

		$dateidToDateName = array();
		foreach($dbresults as $v){
			$dateidToDateName[$v['id']] = $v['date'];
		}

		return $dateidToDateName;
	}

	private function _getCourseTitlesFromIds($ids){
		$where = '';
		$bind = array();
		$i = 0;
		foreach($ids as $k => $v){
			$where .= '( course.id = :dateid'.$i.' ) OR ';
			$bind[':dateid'.$i] = $k;
			$i++;
		}
		$where = rtrim($where, ' OR ');

		$db = $this->_dbconnect();
		$dbresults = $db->newQuery()
			->fields('course.id', 'course.title')
			->tables('course')
			->where($where)
			->select($bind);

		$courseidToCourseTitle = array();
		foreach($dbresults as $v){
			$courseidToCourseTitle[$v['id']] = $v['title'];
		}
		return $courseidToCourseTitle;
	}
}
