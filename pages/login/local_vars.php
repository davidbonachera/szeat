<?php
if ($_POST) {
	if (isset($_POST['notes'])) $_SESSION['user']['notes'] = $db->escape($_POST['notes']);
	if (isset($_POST['email']) && isset($_POST['password'])) {
		if (checkFeild($_POST['email'])) {
			if (checkFeild($_POST['password'])) {
				$logEmail 	= $db->escape($_POST['email']);
				$logPass 	= md5($_POST['password']);
				
				$login = $db->query_first("SELECT * FROM users WHERE email='$logEmail' AND password='$logPass' AND status=1");
				if ($db->affected_rows > 0) {
					
					$_SESSION['user']['id'] = $login['id'];
					
					if (isset($_GET['redirect'])) {
						$redirectURL = urldecode($_GET['redirect']);
						header("Location: $redirectURL");
						exit;
					} else {
						header("Location: index.php?page=account-details");
						exit;
					}
					
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Your email address or password is invalid.';
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Please enter your password.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your email address.';
		}
	} elseif (isset($_POST['email1']) && isset($_POST['password1'])) {
		if (checkFeild($_POST['name'])) {
			if (validEmail($_POST['email1'])) {
				if ($_POST['email1']==$_POST['email2']) {
					if (!checkExists('users','email',$_POST['email1'])) {
						if (checkFeild($_POST['password1'])) {
							if ($_POST['password1']==$_POST['password2']) {
								if (checkFeild($_POST['area'])) {
									if (checkFeild($_POST['building'])) {
										if (checkFeild($_POST['terms'])) {
						
											$data['area_id'] 		= $db->escape($_POST['area']);
											$data['building_id'] 	= $db->escape($_POST['building']);
											$data['apartment'] 		= $db->escape($_POST['apartment']);
											$data['name'] 			= $db->escape($_POST['name']);
											$data['email'] 			= $db->escape($_POST['email1']);
											$data['phone'] 			= $db->escape($_POST['phone']);
											$data['password'] 		= md5($_POST['password1']);
											$data['newsletter'] 	= checkFeild($_POST['newsletter']) ? 1:0;
											$data['status'] 		= 1;
										
											$insert = $db->query_insert("users",$data);
											if ($db->affected_rows > 0) {
												
												$_SESSION['user']['id'] = $insert;
												
												if (isset($_GET['redirect'])) {
													$redirectURL = urldecode($_GET['redirect']);
													header("Location: $redirectURL");
													exit;
												} else {
													$_SESSION['error'] = true;
													$_SESSION['msg']   = 'You\'ve registered successfully.';
												}
												
											} else {
												$_SESSION['error'] = true;
												$_SESSION['msg']   = 'Oops, something went wrong. pleas try again later.';
											}
									
										} else {
											$_SESSION['error'] = true;
											$_SESSION['msg']   = 'You must agree to the terms and conditions';
										}
									} else {
										$_SESSION['error'] = true;
										$_SESSION['msg']   = 'Select you building.';
									}
								} else {
									$_SESSION['error'] = true;
									$_SESSION['msg']   = 'Select your area.';
								}
							} else {
								$_SESSION['error'] = true;
								$_SESSION['msg']   = 'Please enter same password in both fields.';
							}
						} else {
							$_SESSION['error'] = true;
							$_SESSION['msg']   = 'Please enter your password.';
						}
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'The email address is already registered.';
					}
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Please enter same email in both fields.';
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Please enter a valid email address.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your Name.';
		}
	}
}