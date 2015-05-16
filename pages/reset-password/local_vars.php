<?php
if (isset($_GET['x'])) {
	if (checkFeild($_GET['x'])) {
		$code  = $db->escape($_GET['x']);
		$check = $db->query_first("SELECT * FROM users WHERE reset='$code' LIMIT 1");
		if ($db->affected_rows > 0) {
			if ($_POST) {
				if (checkFeild($_POST['password1']) && checkFeild($_POST['password2'])){
					if ($_POST['password1']==$_POST['password2']){

						$data['password'] 	= md5($_POST['password1']);
						$data['reset'] 		= NULL;
						
						if ($db->query_update("users",$data,"id={$check['id']}")) {
							$_SESSION['error'] = false;
							$_SESSION['msg']   = 'Your password has been reset successfully.';
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
			}
		} else {
			header("Location: index.php?invalid-code");
			exit;
		}
	} else {
		header("Location: index.php?code-empty");
		exit;
	}
} else {
	header("Location: index.php");
	exit;
}