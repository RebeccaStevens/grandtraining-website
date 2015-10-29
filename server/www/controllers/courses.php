<?php  namespace GrandTraining\www\controllers;

use \InvalidArgumentException;
use \RuntimeException;
use \UnexpectedValueException;

use AirBase\Util;
use AirBase\PageNotFoundException;

use GrandTraining\www\bases\BaseController;
use GrandTraining\www\models\Courses as Model;

class courses extends BaseController {

  function __construct(){
    parent::__construct();
  }

  /** {@inheritdoc} */
  public function index($data){
    if (Util::arrayHasData($data)) {
      // does the request end with .json
      $jsonRequest;
      try {
        $this->removeFileExtensionFromURLData($data, '.json');
        $jsonRequest = true;
      }
      catch (UnexpectedValueException $e) {
        $jsonRequest = false;
      }

      // if this is a json request
      if ($jsonRequest) {
        try {
          $this->_processJsonRequest($data);
          return;
        }
        catch (RuntimeException $e) {
          throw new PageNotFoundException();
        }
      }
      // if this is not a json request
      else {

      }
      throw new PageNotFoundException();
    }

    $this->_indexPage();
  }

  /**
   * Process a JSON request.
   *
   * @param array $data The url data
   * @throws PageNotFoundException if unable to find the request data
   */
  private function _processJsonRequest(array $data) {
    $dataLength = count($data);

    try {
      switch ($data[0]) {
      // requeset for supercourses
      case 'supercourses':
        if ($dataLength === 1) {
          $this->_jsonSuperCourses();
          return;
        }
        break;

      // request for all courses
      case 'all':
        // all courses of any supercourse
        if ($dataLength === 1) {
          $this->_jsonAllCourses();
          return;
        }
        // all courses of a supercourse
        else if ($dataLength === 2) {
          $this->_jsonAllCoursesOfSuperCourse($data[1]);
          return;
        }
        break;

      // request for courses of a given type, possibly also of a given supercourses
      default:
        // all courses of given course type
        if ($dataLength === 1) {
          $this->_jsonCoursesOfType($data[0]);
          return;
        }
        // all courses of given course type and given supercourse
        else if ($dataLength === 2) {
          $this->_jsonCoursesOfTypeOfSuperCourse($data[0], $data[1]);
          return;
        }
        break;
      }
    }
    catch (InvalidArgumentException $e) { /* throw error below */ }
    throw new RuntimeException('Cannot process json request.');
  }

  //////////////////////////////////////////////////
  // Pages                                        //
  //////////////////////////////////////////////////

  private function _indexPage(){
    $courses_model = new Model();

    $data = array(
        'courses' => $courses_model->getCourses()
    );
    $meta = array(
        'title' => 'Courses - Grand Training',
    );

    $this->_renderPage('courses.php', $data, $meta);
  }

  //////////////////////////////////////////////////
  // Data                                         //
  //////////////////////////////////////////////////

  /**
   * Get as JSON all the super courses.
   * URL: /courses/supercourses.json
   */
  private function _jsonSuperCourses() {
    $model = new Model();
    $superCourses = $model->getSuperCourses();
    $this->sendJson($superCourses);
  }

  /**
   * Get as JSON all the courses (not super courses).
   * URL: /courses/all.json
   */
  private function _jsonAllCourses() {
    $model = new Model();
    $courses = $model->getCourses();
    $this->sendJson($courses);
  }

  /**
   * Get as JSON all of the courses that are part of the given supercourse.
   * URL: /courses/all/{{supercourse}}.json
   *
   * @param string $super The supercourse
   * @throws \InvalidArgumentException if the supercourse doesn't exist
   */
  private function _jsonAllCoursesOfSuperCourse($super) {
    $model = new Model();

    if (!$model->superCoursesExist($super)) {
      throw new InvalidArgumentException("There is no supercourse \"$super\".");
    }

    $courses = $model->getCourses($super);
    $this->sendJson($courses);
  }

  /**
   * Get as JSON all of the courses that are part of the given coures type.
   * URL: /courses/{{coursetype}}.json
   *
   * @param string $type The course type
   * @throws \InvalidArgumentException if the course type doesn't exist
   */
  private function _jsonCoursesOfType($type) {
    $model = new Model();

    if (!$model->coursesTypeExist($type)) {
      throw new InvalidArgumentException("There is not course type \"$type\".");
    }

    $courses = $model->getCourses(null, $type);
    $this->sendJson($courses);
  }

  /**
   * Get as JSON all of the courses that are part of the given course type and of a given supercourse.
   * URL: /courses/{{coursetype}}/{{supercourse}}.json
   *
   * @param string $type The course type
   * @param string $super The supercourse
   * @throws \InvalidArgumentException if either the course type or the supercourse doesn't exist
   */
  private function _jsonCoursesOfTypeOfSuperCourse($type, $super) {
    $model = new Model();

    if (!$model->coursesTypeExist($type) || !$model->superCoursesExist($super)) {
      throw new InvalidArgumentException("Either there is not course type \"$type\" or there is no supercourse \"$super\".");
    }

    $courses = $model->getCourses($super, $type);
    $this->sendJson($courses);
  }
}
