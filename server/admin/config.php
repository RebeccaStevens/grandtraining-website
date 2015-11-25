<?php

define('ENVIRONMENT', 'development');  // (development, testing, production)

require('../gtconfig.php'); // load the global config

// the URL of the website
define('URL', HTTP . $_SERVER['HTTP_HOST'] . substr(dirname($_SERVER['PHP_SELF']), 0, -1) . '/');

/* ********************* */
/*   paths               */
/* ********************* */
define('PATH_MODELS',      'models/');
define('PATH_VIEWS',       'views/');
define('PATH_CONTROLLERS', 'controllers/');
define('PATH_LIBRARIES',   '../vendor/');
define('PATH_ERROR_PAGES', 'errors/');

define('NAMESPACE_MODELS', "GrandTraining\\admin\\models\\");
define('NAMESPACE_CONTROLLERS', "GrandTraining\\admin\\controllers\\");

/* ********************* */
/*   files               */
/* ********************* */
define('FILE_HEADER', 'private/viewfiles/header.php');
define('FILE_FOOTER', 'private/viewfiles/footer.php');

define('FILE_BASE_CONTROLLER', 'private/Base_Controller.php');
define('FILE_BASE_MODEL',      'private/Base_Model.php');

/* ********************* */
/*   Google Login        */
/* ********************* */

define('GOOGLE_LOGIN_CLIENT_ID',      '282409204527-cnskvl5ldf6mh44h18dbt2rpcnnv9ki3.apps.googleusercontent.com');
define('GOOGLE_LOGIN_CLIENT_SECRET',  'i0TPj4Z113PzZEzdmesr08VM');
define('GOOGLE_LOGIN_REDIRECT_URI',   'http://localhost:5000/login/oauth2');
define('GOOGLE_LOGIN_SCOPES',         'email');
// define('GOOGLE_LOGIN_HOSTED_DOMAIN',  'grandtraining.co.nz');

/* ********************* */
/*   pages               */
/* ********************* */
define('URL_LOGIN',        URL.'login');
