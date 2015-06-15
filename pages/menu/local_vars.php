<?php
if (isset($_GET['id'])) {
	$rID = $db->escape($_GET['id']);
	$res = $db->query_first("SELECT * FROM restaurants WHERE id=$rID");
	
	if (isset($_SESSION['user']['restaurant']['id']) && $_SESSION['user']['restaurant']['id']!=$res['id']) {
		if (isset($_SESSION['user']['items'])) {
			unset($_SESSION['user']['items']);
		}
	}
	
	$_SESSION['user']['restaurant']['name'] = $res['name'];
	$_SESSION['user']['restaurant']['name_cn'] = $res['name_cn'];
	$_SESSION['user']['restaurant']['id'] 	= $res['id'];
	
} elseif (isset($_GET['restaurant'])) {
	$str = str_replace(array('_','+'), array('&',' '), html_entity_decode($_GET['restaurant']));
	$res = $db->query_first("SELECT * FROM restaurants WHERE name LIKE '%$str%' AND status=1 LIMIT 1");
	
	if (isset($_SESSION['user']['restaurant']['id']) && $_SESSION['user']['restaurant']['id']!=$res['id']) {
		if (isset($_SESSION['user']['items'])) {
			unset($_SESSION['user']['items']);
		}
	}
	
	$_SESSION['user']['restaurant']['name'] = $res['name'];
	$_SESSION['user']['restaurant']['name_cn'] = $res['name_cn'];
	$_SESSION['user']['restaurant']['id'] 	= $res['id'];
} else {
	header("Location: index.php");
	exit;
}
if ($db->affected_rows > 0) {
	// do nothing
} else {
	header("Location: index.php?404");
	exit;
}

$currentDay = strtoupper(date("l"));

$time = $db->query("SELECT * FROM delivery_times WHERE CURRENT_TIME() BETWEEN TIME(`start`) AND TIME(`end`) AND day='$currentDay' AND restaurant_id={$_SESSION['user']['restaurant']['id']} AND status=1");

if ($db->affected_rows > 0) {
	$deliveryAvailable = true;
} else {
	$deliveryAvailable = false;
}

// $deliveryAvailable = true;