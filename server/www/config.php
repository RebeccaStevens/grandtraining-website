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

define('PATH_LIBRARIES',  '../vendor/');

define('PATH_CSS',        URL.'assets/css/');
define('PATH_JS',         URL.'assets/js/');
define('PATH_IMAGES',     URL.'assets/images/');
define('PATH_ELEMENTS',   URL.'assets/gt-elements/');
define('PATH_COMPONENTS', URL.'assets/libraries/components/');

define('PATH_ERROR_PAGES', 'errors/');

/* ********************* */
/*   files               */
/* ********************* */
define('FILE_HEADER', 'private/viewfiles/header.php');
define('FILE_FOOTER', 'private/viewfiles/footer.php');

define('FILE_BASE_CONTROLLER', 'private/Base_Controller.php');
define('FILE_BASE_MODEL',      'private/Base_Model.php');

/* ********************* */
/*   pages               */
/* ********************* */
define('URL_LOGIN',        URL.'login/');
