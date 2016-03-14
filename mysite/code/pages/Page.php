<?php

class Page extends SiteTree {

	private static $db = array(
	);

	private static $has_one = array(
	);

}
class Page_Controller extends ContentController {

	private static $allowed_actions = array();

	public function init() {
		parent::init();
		// You can include any CSS or JS required by your project here.
		// See: http://doc.silverstripe.org/framework/en/reference/requirements
	}

	public function index(SS_HTTPRequest $request) {
		// if this is an ajax request
		// (`isset($_GET['ajax']` is needed for error pages)
	    if($request->isAjax() || isset($_GET['ajax'])) {
	        return $this->renderWith($this->RecordClassName);
	    }
		else {
            return array();
        }
	}

	/**
	 * This is the current section's route.
	 */
	public function Route() {
		$path = $this->request->getUrl();

		// normal page?
		if ($path) {
			$path = strtolower($path);
		}
		// error page?
		else {
			// error pages are always at top level so URLSegment will work
			$path = Director::get_current_page()->URLSegment;
		}

		return $path;
	}

}
