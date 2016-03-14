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
	    if($request->isAjax()) {
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
		$path = strtolower($this->request->getUrl());

		// default to home
		if ($path === '') {
			$path = 'home';
		}

		return $path;
	}

	/**
	 * Absolute link to the 404 Page Not Found page.
	 */
	public function LinkPageNotFound() {
		return Director::baseURL() . 'page-not-found/';
	}

}
