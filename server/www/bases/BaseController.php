<?php namespace GrandTraining\www\bases;

use AirBase\Controller;
use AirBase\View;

abstract class BaseController extends Controller {

	function __construct() {
		parent::__construct();
	}

	/**
	 * If the user is not logged in, then redirect them to the given path.
	 *
	 * @param $redirect the path to redirect the user to if they are not logged in
	 */
	protected function _requireLoggedIn($redirect=null) {
		try{
			parent::_requireLoggedIn();
		}
		catch(NotLoggedInException $e) {
			if(isset($redirect)) {
				header('location: '.$redirect);
				exit;
			}
			throw $e;
		}
	}

	/**
	 * If the user is logged in, then redirect them to the given path.
	 *
	 * @param $redirect the path to redirect the user to if they are logged in
	 */
	protected function _requireLoggedOut($redirect=null) {
		try{
			parent::_requireLoggedOut();
		}
		catch(NotLoggedOutException $e) {
			if(isset($redirect)) {
				header('location: '.$redirect);
				exit;
			}
			throw $e;
		}
	}

	/**
	 * Get the View object of the page.
	 */
	protected function _loadView($file, $renderHeaderAndFooter=true) {
		if($renderHeaderAndFooter) {
			return new View(array(FILE_HEADER, PATH_VIEWS.$file, FILE_FOOTER));
		}
		return new View(array(PATH_VIEWS.$file));
	}

	protected function _isAjaxRequest() {
		return isset($_POST['ajax']) && $_POST['ajax'] == 'true';
	}

	protected function _renderPage($viewFile, array $data, array $meta=null, array $styleSheets=null, array $javaScriptFiles=null, array $metaTags=null) {
		$ajax = $this->_isAjaxRequest();
		$view = $this->_loadView($viewFile, !$ajax);

		if(isset($styleSheets))
			$view->addStyleSheet($styleSheets);
		if(isset($javaScriptFiles))
			$view->addJavaScriptFile($javaScriptFiles);
		if(isset($metaTags))
			$view->addMetaData($metaTags);

		if($ajax) { $view->renderAsJSON($data, $meta); }
		else{ $view->render(isset($meta) ? array_merge($data, $meta) : $data); }
	}
}
