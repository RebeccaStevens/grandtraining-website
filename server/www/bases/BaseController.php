<?php namespace GrandTraining\www\bases;

use \UnexpectedValueException;

use AirBase\Controller;
use AirBase\NotLoggedInException;
use AirBase\NotLoggedOutException;
use AirBase\Util;
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
			parent::_requireUserLoggedIn();
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
			parent::_requireUserLoggedOut();
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

	/**
	 * Send the given data to the user as JSON.
	 *
	 * @param mixed $data The data to send
	 */
	protected function sendJson($data) {
		header('Content-Type: application/json');
    echo json_encode($data);
	}

	/**
	 * Remove the given file extension from the url data array.
	 * Modifies $data.
	 *
	 * @param array $data The url data
	 * @param string $extension The file extension to remove
	 * @throws UnexpectedValueException if the url doesn't end with the given extension
	 */
	protected function removeFileExtensionFromURLData(array &$data, $extension) {
		$length = count($data);

		if (!Util::stringEndsWith($data[$length-1], $extension)) {
      throw new UnexpectedValueException("url doesn't end with $extension");
    }

		$data[$length-1] = substr($data[$length-1], 0, strlen($data[$length-1]) - strlen($extension));
	}
}
