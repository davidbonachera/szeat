<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once("class/class.phpmailer.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php isLoggedIn(); ?>
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Change Password - <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
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
                    <a href="account-details.php">Customer Dashboard</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Change Password</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<!-- your-order -->
        	<div class="span6">
            	<div class="your-info gray-bg-container">
                	<h2>Change Password</h2>
                    <!-- your-info-registration -->
                    <div class="your-info-registration">
                        <form action="" method="post">
                            <div class="registration-row">
                            	<label>Current Password:</label>
                                <input type="password" name="currentPassword" />
                            </div>
                            <div class="registration-row">
                            	<label>New Password:</label>
                                <input type="password" name="password1" />
                            </div>
                            <div class="registration-row">
                            	<label>Re-enter New Password:</label>
                                <input type="password" name="password2" />
                            </div>
                            <div class="clearfix"></div>
                            <button type="submit" class="yellow-button">Submit</button>
                            
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
</body>
</html>
