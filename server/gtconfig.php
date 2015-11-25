<?php

if(defined('ENVIRONMENT')){
  switch (ENVIRONMENT) {
    case 'development' :
      error_reporting(E_ALL);
      break;

    case 'testing' :
    case 'production' :
      error_reporting(0);
      break;

    default :
      exit('The application environment is not set correctly.');
  }
}

// allow the session cookie to be used on all subdomains
// ini_set('session.cookie_domain', '.grandtraining.local');

define('HTTP', (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) ? 'https://' : 'http://');

// the URL of the gt website
define('GTURL', HTTP.'www.grandtraining.local/');

// the URL of the admin website
define('ADMINURL', HTTP.'admin.grandtraining.local/');

// the URL of the etrain website
define('ETRAINURL', HTTP.'etrain.co.nz/');

// the URL of the facebook page
define('GTFBURL', 'https://www.facebook.com/GrandTraining');


/* ********************* */
/*   database settings   */
/* ********************* */
define('DB_TYPE', 'pgsql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'grandtraining');
define('DB_USER', 'username');
define('DB_PASS', 'password');
