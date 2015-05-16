<?php
if ($_POST) {
	if (checkFeild($_POST['currentPassword'])){
		
		$currentPass = md5($_POST['currentPassword']);
		$check 		 = $db->query_first("SELECT * FROM users WHERE password='$currentPass' AND id={$_SESSION['user']['id']}");
		
		if ($db->affected_rows > 0) {
			if (checkFeild($_POST['password1']) && checkFeild($_POST['password2'])){
				if ($_POST['password1']==$_POST['password2']){
		
					$data['password'] 	= md5($_POST['password1']);
					
					if ($db->query_update("users",$data,"id={$_SESSION['user']['id']}")) {
						$_SESSION['error'] = false;
						$_SESSION['msg']   = 'Your password has been changed successfully.';
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
					}
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg'] = 'Please enter same passwords in both fields.';
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg'] = 'Please fill both password fields.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg'] = 'Current password is incorrect.';
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg'] = 'Please type your current password.';
	}
}