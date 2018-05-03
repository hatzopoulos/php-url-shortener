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

define('TWITTER_USERNAME', 'hatzopoulos');
define('GOOGLE_PLUS_ID', '+AnthonyHatzopoulos');
define('SHORT_URL', 'https://ahatz.com/'); // include the trailing slash!
define('DEFAULT_URL', 'https://hatzopoulos.ca'); // omit the trailing slash!
