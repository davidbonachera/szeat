<?php isLoggedIn(); ?>
<?php
if ($_POST) {
	if (checkFeild($_POST['name'])) {
		if (checkFeild($_POST['phone'])) {
			if (checkFeild($_POST['area'])) {
				if (checkFeild($_POST['building'])) {
					
					$data['name'] 			= $db->escape($_POST['name']);
					$data['phone'] 			= $db->escape($_POST['phone']);
					$data['area_id'] 		= $db->escape($_POST['area']);
					$data['building_id'] 	= $db->escape($_POST['building']);
					$data['apartment'] 		= $db->escape($_POST['apartment']);
					
					if ($db->query_update("users",$data,"id={$_SESSION['user']['id']}")) {
						$_SESSION['error'] = false;
						$_SESSION['msg']   = 'Your details have been saved successfully.';
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
					}
					
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Please select your building.';
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Please select your area.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your phone number.';
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg']   = 'Please enter your phone name.';
	}
}