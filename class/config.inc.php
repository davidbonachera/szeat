<?php
## UNCOMMENT BELOW 2 LINES FOR MAINTENENCE MODE ##
## echo "<h1>We'll be back shortly</h1>";
## exit;

ob_start();
session_start();
error_reporting (0); // E_ALL ^ E_NOTICE
date_default_timezone_set('Asia/Hong_Kong');

require("dblogin.inc.php");
// pull in the file with the database class
require("Database.class.php");

// create the $db ojbect
$db = new Database($config['server'], $config['user'], $config['pass'], $config['database']);

// connect to the server
$db->connect();

// Change encoding to UTF-8, required to avaoid weired characters in text.
$UTF8 = $db->query("SET NAMES 'utf8'");
$SQLT = $db->query("SET time_zone = '+8:00'");

// General Variables
$site = $db->query_first("SELECT * FROM config WHERE id=1");

define("_title", 	html_entity_decode($site['title']));
define("_tagline", 	html_entity_decode($site['tagline']));
define("_email", 	$site['email']);
define("_twitter", 	$site['twitter']);
define("_facebook", $site['facebook']);
define("_self", 	$_SERVER['PHP_SELF']);
define("_priceSymbol", $site['currency']);
define("_nowdt", date('Y-m-d H:i:s'));

if (!isset($pData)) 						 $pData = array();
if (!isset($uData)) 						 $uData = array();
if (!isset($yearsArray)) 					 $yearsArray = array();
if (!isset($_SESSION['user'])) 				 $_SESSION['user'] = array();
if (!isset($_SESSION['user']['items'])) 	 $_SESSION['user']['items'] = array();
if (!isset($_SESSION['user']['restaurant'])) $_SESSION['user']['restaurant'] = array();

if (!is_writable(session_save_path())) {
    echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
}

// set page
if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 'home';
}

// language select
if (isset($_GET['lang'])) {
	$lang = $_GET['lang'];
	$_SESSION['lang'] = $lang;
} else {
	if (isset($_SESSION['lang'])) {
		$lang = $_SESSION['lang'];
	} else {
		$lang = 'en';
	}
}


?>