<?php  namespace GrandTraining\admin\models;

use AirBase\Session;
use \Google_Client;

use GrandTraining\www\bases\BaseModel;

class Login extends BaseModel {

  /** @var Google_Client Google Client login API */
  protected $_client;

  /**
   * Create the Login model.
   */
  function __construct() {
    $this->_client = new Google_Client();
		$this->_client->setClientId(GOOGLE_LOGIN_CLIENT_ID);
		$this->_client->setClientSecret(GOOGLE_LOGIN_CLIENT_SECRET);
		$this->_client->setRedirectUri(GOOGLE_LOGIN_REDIRECT_URI);
		$this->_client->setScopes(GOOGLE_LOGIN_SCOPES);
    if (defined('GOOGLE_LOGIN_HOSTED_DOMAIN')) {
      $this->_client->setHostedDomain(GOOGLE_LOGIN_HOSTED_DOMAIN);
    }
  }

  /**
   * Returns whether or not the user is logged in.
   *
   * @return boolean
   */
  public static function isLoggedIn() {
    return Session::hasKeyValue('access_token');
  }

  /**
   * Log the user out.
   */
  public static function logout() {
    SESSION::clear('access_token');
  }

  /**
   * Get the URL the user needs to go to in order to sign in to Google.
   *
   * @return string The URL
   */
  public function getAuthUrl() {
    return $this->_client->createAuthUrl();
  }

  /**
   * Check if we were given a code.
   * If so, try and authenticate with google using it.
   *
   * @throws Google_Auth_Exception
   * @return boolean Whether or not a token was given
   */
  public function checkRedirectCode() {
    $code = $_GET['code'];
    if (!isset($code)) {
      return false;
    }

    $this->_client->authenticate($code);
    $this->_setAccessToken($this->_client->getAccessToken());
    return true;
  }

  /**
   * Set the access token.
   *
   * @param string The access token
   */
  private function _setAccessToken($token) {
    Session::set('access_token', $token);
    $this->_client->setAccessToken($token);
  }
}
