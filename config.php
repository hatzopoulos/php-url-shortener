<?php

// Script being called directly so force forbidden response
if (str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', __FILE__) === $_SERVER['SCRIPT_NAME']) {
    header('Content-Type: text/plain;charset=UTF-8', true, 403);
    die;
}

define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'user');
define('MYSQL_PASSWORD', 'password');
define('MYSQL_DATABASE', 'database');
define('TWITTER_USERNAME', 'mathias');
define('GOOGLE_PLUS_ID', '106697091536876736486');
define('SHORT_URL', 'https://mths.be/'); // include the trailing slash!
define('DEFAULT_URL', 'https://mathiasbynens.be'); // omit the trailing slash!

?>
