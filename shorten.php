<?php

// Script being called directly so force forbidden response.
if (empty($_SERVER['REDIRECT_URL']) && str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', __FILE__) === $_SERVER['SCRIPT_NAME']) {
    header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden', true, 403);
    die;
}

ob_start();

header('Content-Type: text/plain;charset=UTF-8');

require_once __DIR__.'/config.php';

$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';

// If the url doesn't start with http, don't use it.
// @todo: perhaps we can guess the protocol scheme.
if ('http' !== substr($url, 0, strlen('http'))) {
	$url = '';
}

$bad_urls = array(
	'about:blank',
	'undefined',
	'http://localhost/',
	'https://localhost/',
	'http://127.0.0.1/',
	'https://127.0.0.1/',
);
if (in_array($url, array_merge(array('', 'http://', 'https://'), $bad_urls))) {
	die('Enter an URL.');
}
foreach ($bad_urls as $bad_url) {
	if (strpos($url, $bad_url) === 0) {
		die('Enter a good URL.');
	}
}

// If the URL is already a short URL on this domain, don’t re-shorten it
if (strpos($url, SHORT_URL) === 0) {
	die($url);
}

$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
$db->set_charset('utf8mb4');

$url = $db->real_escape_string($url);

$result = $db->query('SELECT slug FROM redirect WHERE url = "' . $url . '" LIMIT 1');
if ($result && $result->num_rows > 0) {
	// There’s already a short URL for this URL.
	die(SHORT_URL . $result->fetch_object()->slug);
}

// Create the redirect slug and url and insert it.
$result = $db->query('SELECT slug, url FROM redirect ORDER BY date DESC, slug DESC LIMIT 1');
if ($result && $result->num_rows > 0) {

	function nextLetter(&$str) {
		$str = ('z' == $str ? 'a' : ++$str);
	}

	function getNextShortURL($s) {
		$a = str_split($s);
		$c = count($a);
		if (preg_match('/^z*$/', $s)) { // string consists entirely of `z`.
			return str_repeat('a', $c + 1);
		}
		while ('z' == $a[--$c]) {
			nextLetter($a[$c]);
		}
		nextLetter($a[$c]);
		return implode($a);
	}

	$slug = getNextShortURL($result->fetch_object()->slug);
	if ($db->query('INSERT INTO redirect (slug, url, date, hits) VALUES ("' . $slug . '", "' . $url . '", NOW(), 0)')) {
		header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
		echo SHORT_URL . $slug;
		$db->query('OPTIMIZE TABLE `redirect`');
	}
}
