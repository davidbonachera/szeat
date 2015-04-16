<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once("class/class.phpmailer.php"); ?>
<?php require_once('includes/functions.php'); ?>
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forgot Password - <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="login.php">Login / Signup</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Forgot Password</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<!-- your-order -->
        	<div class="span6">
            	<div class="your-info gray-bg-container">
                	<h2>Reset Your Password</h2>
                    <!-- your-info-registration -->
                    <div class="your-info-registration">
                        <form action="" method="post">
                            <div class="registration-row">
                            	<label>E-mail Address:</label>
                                <input type="email" name="email" />
                            </div>
                            <div class="clearfix"></div>
                            <a href="login.php" class="forgot-password">&laquo; back to login</a>
                            <div class="clearfix"></div>
                            <button type="submit" class="yellow-button">Reset</button>
                            
                            <div class="clearfix">&nbsp;</div>
                            <?php if (isset($_SESSION['msg'])) { ?>
	                            <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                                	<br /><?php echo $_SESSION['msg']; ?>
                                </div>
                            <?php } // isset $_SESSION['msg'] ?>
                        </form>
                	</div>
                    <!-- end of your-info-registration -->
                </div>
            </div>
        	<!--end of your-order -->
            
        </div>
    	<!-- end of main content -->
    </div>
    <?php require_once('includes/footer.php'); ?>
<?php if (isset($_SESSION['error'])) unset($_SESSION['error'],$_SESSION['msg']); ?>
</body>
</html>
