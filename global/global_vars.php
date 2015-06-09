<?php

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


$status = ($lang=='cn'?'status_cn':'status');

$datecountry = ($lang=='cn' ? "Y/m/d" : "d/m/Y");