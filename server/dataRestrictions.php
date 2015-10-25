<?php

// `user` table restrictions
define('USER_USERNAME_MIN_LENGTH',  2);             define('USER_USERNAME_MIN_LENGTH_MSG',   'Your username must be atleast 2 characters long');
define('USER_USERNAME_MAX_LENGTH', 32);             define('USER_USERNAME_MAX_LENGTH_MSG',   'Your username cannot be more than 32 characters long');
define('USER_PASSWORD_MIN_LENGTH',  6);             define('USER_PASSWORD_MIN_LENGTH_MSG',   'Your password must be atleast 6 characters long');
define('USER_PASSWORD_MAX_LENGTH', 32);             define('USER_PASSWORD_MAX_LENGTH_MSG',   'Your password cannot be more than 32 characters long');
define('USER_FNAME_MIN_LENGTH',     2);             define('USER_FNAME_MIN_LENGTH_MSG',      'Your first name must be atleast 2 characters long');
define('USER_FNAME_MAX_LENGTH',    24);             define('USER_FNAME_MAX_LENGTH_MSG',      'Your first name cannot be more than 24 characters long');
define('USER_SNAME_MIN_LENGTH',     2);             define('USER_SNAME_MIN_LENGTH_MSG',      'Your surname must be atleast 2 characters long');
define('USER_SNAME_MAX_LENGTH',    24);             define('USER_SNAME_MAX_LENGTH_MSG',      'Your surname cannot be more than 24 characters long');
define('USER_DATE_OF_BIRTH_EARLIST', '1900-01-01'); define('USER_DATE_OF_BIRTH_EARLIST_MSG', 'Your date of birth cannot be before 1 January 1900');
define('USER_OTHERNOTES_MAX_LENGTH', '65535');      define('USER_OTHERNOTES_MAX_LENGTH_MSG', 'Length is too long, 65-535 character max');
