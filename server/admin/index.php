<?php

/**
 * This is the main page.  It is the only page the users directly visits.
 * When a page is requested, the Bootstrap will process the request and call the
 * appropriate controller which will give the user the page they want.
 */

require('config.php');
require(__DIR__ . '/../vendor/autoload.php');   // Autoloader
require(PATH_LIBRARIES.'slaymaster3000/airbase-php/vendor/autoload.php'); // AirBase autoloader

use AirBase\AirBase;
use AirBase\Session;
use AirBase\Bootstrap;
use AirBase\PageNotFoundException;
use AirBase\NotLoggedInException;
use AirBase\NotLoggedOutException;

use GrandTraining\admin\models\Login as LoginModel;

// init the lib library
AirBase::init();
AirBase::setIsLoggedInFunction(function() {
  return LoginModel::isLoggedIn();
});

try {
  Session::start();                                     // start a session
  $bootstrap = new Bootstrap(NAMESPACE_CONTROLLERS, PATH_CONTROLLERS, 'url');  // process the request
}
// Errors
catch (PageNotFoundException $e) {
  if (AirBase::isLoggedIn()) {
    require PATH_ERROR_PAGES.'404.php';
  }
  header('location: '.URL);          // always redirect to the home page if not logged in
  exit;
}
catch (NotLoggedInException $e) {
  header('location: '.URL_LOGIN);    // by default, redirect the user to the login page
  exit;
}
catch (NotLoggedOutException $e) {
  header('location: '.URL);          // by default, redirect the user to the home page
  exit;
}
catch (PDOException $e) {
  if (ENVIRONMENT === 'development') {
    throw $e;
  }
  echo('A database error has occurred.');
}
catch (Exception $e) {
  if (ENVIRONMENT === 'development') {
    throw $e;
  }
  echo("Something Went Wrong.");
}
