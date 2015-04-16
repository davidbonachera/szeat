<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
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
						header("Location: account-details.php");
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login / Signup - <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
    <script type="text/javascript" charset="utf-8">
	$(function(){
	  $("select#area").change(function(){
		$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		  $("select#building").html(options);
		})
	  })
	})
	</script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Login / Signup</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	 <?php if (isset($_SESSION['msg'])) { ?>
                <div class="clearfix"></div>
                <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>" style="text-align:center;">
                    <?php echo $_SESSION['msg']; ?>
                </div>
                <div class="clearfix">&nbsp;</div>
            <?php } // isset $_SESSION['msg'] ?>
        	<!-- your-order -->
        	<div class="span6">
            	<div class="your-info gray-bg-container">
                	<h2>Name and Address</h2>
                    <!-- your-info-registration -->
                    <div class="your-info-registration">
                        <form action="" method="post">
                        	<h3>Existing Member</h3>
                            <div class="registration-row">
                            	<label>E-mail Address:</label>
                                <input type="email" name="email" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email'])) ? $_POST['email']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Password:</label>
                                <input type="password" name="password" autocomplete="off" />
                            </div>
                            <a href="forgot-password.php" class="forgot-password">Forgotten your password?</a>
                            <button type="submit" class="yellow-button">Login</button>

                        </form>
                	</div>
                    <!-- end of your-info-registration -->
                </div>
            </div>
        	<!--end of your-order -->
            
        	<!-- your-info -->
        	<div class="span6">
            	<div class="your-info gray-bg-container">
                    <div class="your-info-registration">
                        <form action="" method="post">
                        	<h3>New Member</h3>
                            <div class="registration-row">
                            	<label>Name:</label>
                                <input type="text" name="name" id="name" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['name'])) ? $_POST['name']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Phone:</label>
                                <input type="text" name="phone" id="phone" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['phone'])) ? $_POST['phone']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Your Area:</label>
                                <select name="area" id="area">
                                	<option hidden="hidden" value="">Select</option>
									<?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                        <option value="<?php echo $r['id']; ?>" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['area']==$r['id']) ? 'selected':NULL; ?>>
											<?php __($r['title']); ?>
                                        </option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                            <div class="registration-row">
                            	<label>Your Building:</label>
                                <select name="building" id="building">
                                	<option hidden="hidden" value="">Select</option>
									<?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                        <option value="<?php echo $r['id']; ?>" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['building']==$r['id']) ? 'selected':NULL; ?>>
											<?php __($r['title']); ?>
                                        </option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </div>
                            <div class="registration-row">
                            	<label>Your Block/ Apt #:</label>
                                <input type="text" name="apartment" id="apartment" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['apartment'])) ? $_POST['apartment']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>E-mail address:</label>
                                <input type="email" name="email1" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email1'])) ? $_POST['email1']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Re-enter Email addres:</label>
                                <input type="email" name="email2" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['email2'])) ? $_POST['email2']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Password:</label>
                                <input type="password" name="password1" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['password1'])) ? $_POST['password1']:NULL; ?>" />
                            </div>
                            <div class="registration-row">
                            	<label>Re-enter Password:</label>
                                <input type="password" name="password2" value="<?php echo (isset($_POST) && $_SESSION['error']==true && isset($_POST['password2'])) ? $_POST['password2']:NULL; ?>" />
                            </div>
                            <div class="registration-row checkbox-row">
                            	<input type="checkbox" name="newsletter" value="1" <?php echo ($_POST) ? ($_POST['newsletter']==1 ? 'checked':NULL):'checked'; ?> />
                                <p>I would like to sign up to the SHENZHEN EAT newsletter (via email and mobile) for the chance to win free takeaway for a year.</p>
                           	</div> 
                            <div class="registration-row checkbox-row">
                            	<input type="checkbox" name="terms" value="1" <?php echo (isset($_POST) && $_SESSION['error']==true && $_POST['terms']==1) ? 'checked':NULL; ?> />
                                <p>I accept the <a href="#">terms and conditions</a>, <a href="#">privacy policy</a> & <a href="#">cookies policy</a></p>
                           	</div> 
                            <button name="submit" type="submit" class="yellow-button">Next</button>
                        </form>
                	</div>
                    <!-- end of your-info-registration -->
                </div>
            </div>
        	<!-- end of your-info -->
        </div>
    	<!-- end of main content -->
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>
