<?php 
if ($_POST) {
	if (isset($_POST['email'])) {
		if (checkFeild($_POST['email']) && validEmail($_POST['email'])) {
			$rEmail = $db->escape($_POST['email']);
			$check = $db->query_first("SELECT * FROM users WHERE email='$rEmail' LIMIT 1");
			if ($db->affected_rows > 0) {
				
				$data['reset'] = substr(md5(rand(123456789,time())),0,10);
				$link = $config['siteURL']."reset-password.php?x=".$data['reset'];
				
				if ($db->query_update("users",$data,"id={$check['id']}")) {
					
					$template	= $db->query_first("SELECT * FROM email_templates WHERE name='reset_password' LIMIT 1");
					
					$from_name	= $template['from_name'];
					$from_email	= $template['from_email'];
					$subject 	= $template['subject'];
					
					$messageBody = html_entity_decode($template['body']);
					$messageBody = str_replace("#NAME#", 	$check['name'], 	$messageBody);
					$messageBody = str_replace("#LINK#", 	$link, 				$messageBody);

					if (sendEmail($from_name, $from_email, $check['email'], $subject, $messageBody)) {
						$_SESSION['error'] = false;
						$_SESSION['msg']   = 'An email will be sent with instruction on how to reset your password. Please check your spam folder if you don\'t recieve it in next 5 minutes.';
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'Oops, email wasn\'t sent. Please try again later.';
					}
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
				}
				
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Email address not found.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your email address.';
		}
	}
}