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
		return $this->__render($request);
	}

	/**
	 * This is the current section's route.
	 *
	 * @return string Route
	 */
	public function Route() {
		$path = $this->request->getUrl();

		// normal page?
		if ($path) {
			$path = strtolower($path);
		}
		// other page?
		else {
			$path = strtolower(Director::get_current_page()->request->getUrl());
		}
		return $path;
	}

	/**
	 * Return an ArrayList of the Genders that can be used.
	 * Can be looped over in a template.
	 *
	 * @returns ArrayList
	 */
	public function Genders() {
		return new ArrayList(singleton('Person')->dbObject('Gender')->enumValues());
	}

	/**
	 * Send the given data as JSON to the client.
	 *
	 * @param array $data
	 */
	protected function sendJSON(array $data = null) {
		if ($data === null) {
			$data = array();
		}
		$this->response->addHeader('Content-Type', 'application/json');
		return json_encode($data);
	}

	/**
	 * Get the templates to use for ajax requests
	 *
	 * @return array
	 */
	protected function getAjaxTemplates() {
		$action = '_' . $this->getAction();

		$templates = array_merge(
			SSViewer::get_templates_by_class(get_class($this->dataRecord), $action, get_class($this->dataRecord)),
			SSViewer::get_templates_by_class(get_class($this), $action, get_class($this)),
			SSViewer::get_templates_by_class(get_class($this->dataRecord), '', get_class($this->dataRecord)),
			SSViewer::get_templates_by_class(get_class($this), '', get_class($this))
		);
		return $templates;
	}

	/**
	 * Action that render to the screen should call and return this method.
	 */
	protected function __render(SS_HTTPRequest $request, $params = array()) {
		// if this is an ajax request
		// (`isset($_GET['ajax']` is needed for error pages)
	    if($request->isAjax() || isset($_GET['ajax'])) {
	        return $this->customise($params)->renderWith($this->getAjaxTemplates());
	    }
		else {
            return $params;
        }
	}

}
