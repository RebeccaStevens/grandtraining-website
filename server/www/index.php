<?php

/**
 * This is the main page.  It is the only page the users directly visits.
 * When a page is requested, the Bootstrap will process the request and call the
 * appropriate controller which will give the user the page they want.
 */

require('config.php');
require(PATH_LIBRARIES.'slaymaster3000/sm3-php-framework/lib/Lib.php');

use lib\Lib;
use lib\Session;
use lib\Bootstrap;
use lib\PageNotFoundException;
use lib\NotLoggedInException;
use lib\NotLoggedOutException;

// init the lib library
Lib::init(PATH_LIBRARIES.'slaymaster3000/sm3-php-framework/');
Lib::setIsLoggedInFunction(function(){
  return Session::get('user_id') > 0;
});

// Auto loader: Load class files automatically form the libraries folder.
spl_autoload_register(function($class) {
  require(PATH_LIBRARIES . $class . '.php');
});

try{
  Session::start();                  // start a session
  $bootstrap = new Bootstrap(PATH_CONTROLLERS, 'url');  // process the request
}
// Errors
catch(PageNotFoundException $e){
  require PATH_ERROR_PAGES.'404.php';
  exit;
}
catch(NotLoggedInException $e){
  header('location: '.URL_LOGIN);    // by default, redirect the user to the login page
  exit;
}
catch(NotLoggedOutException $e){
  header('location: '.URL);          // by default, redirect the user to the home page
  exit;
}
//catch(PDOException $e){
//  echo('A database error has occurred.');
//}
//catch(Exception $e){
//  echo("Something Went Wrong.");
//}
