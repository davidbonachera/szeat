<?php

if (!isset($_SESSION['user']['items']) || sizeof($_SESSION['user']['items']) < 1) { 
	$_SESSION['error'] = true;
	$_SESSION['msg'] = "Please add items to your order.";
	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;
}