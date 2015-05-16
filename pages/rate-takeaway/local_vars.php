<?php
if (isset($_GET['order'])) {
	$orderID = $db->escape($_GET['order']);
	$order = $db->query_first("SELECT * FROM orders WHERE id=$orderID AND user_id={$_SESSION['user']['id']} AND status=1");
	if ($db->affected_rows < 1) {
		header("Location: account-details.php?order-unavailable");
		exit;
	}
} else {
	header("Location: account-details.php?order-not-found");
	exit;
}

if ($_POST) {
	if (checkFeild($_POST['rating'])) {
		
		$data['user_id'] 		= $_SESSION['user']['id'];
		$data['order_id'] 		= $orderID;
		$data['restaurant_id'] 	= $order['restaurant_id'];
		$data['ratings'] 		= $db->escape($_POST['rating']);
		$data['comments'] 		= $db->escape(strip_tags($_POST['comments'],NULL));
		$data['status']			= 0;
		
		if ($db->query_insert("ratings",$data)) {
			$_SESSION['error'] = false;
			$_SESSION['msg']   = 'Your ratings have been saved successfully.';
			header("Location: rate-takeaway.php?order=$orderID");
			exit;
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
		}
		
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg']   = 'Please select star rating.';
	}
}