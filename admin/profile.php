<?php require_once("../class/config.inc.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'profile.php',
					'title'	=> 'Profile'
				)
			);
?>
<?php
if($_POST) {
	if (checkFeild($_POST["name"]) && checkFeild($_POST["email"])) {
		if(validEmail(trim($_POST["email"]))) {
			
			if (checkFeild($_POST['old_password']) && checkFeild($_POST['new_password'])) {
				
				$password = md5($_POST['old_password']);
				$db->query_first("SELECT id FROM admin WHERE id={$_SESSION['aid']} AND password='$password'");
				
				if ($db->affected_rows > 0) {
					if (
						checkFeild($_POST['new_password']) && 
						$_POST['new_password']!="old_password" && 
						$_POST['new_password']==$_POST['new_password2']
					   ) {
						
						$_SESSION['error'] 	= false;
						$data['password'] 	= md5($_POST['new_password']);
					
					} else {
						$_SESSION['msg'] = "Oops, your passwords don't match.";
						$_SESSION['error'] = true;
					}
				} else {
					$_SESSION['msg'] = "Your old password is incorrect.";
					$_SESSION['error'] = true;
				}
			} else {
				$_SESSION['error'] = false;
			}
			
			if ($_SESSION['error']==false) {
				
				$data['name'] 		= htmlentities($_POST['name']);
				$data['email'] 		= $_POST['email'];

				if($db->query_update("admin", $data, "id={$_SESSION['aid']}")) {
					$_SESSION['msg'] = "User profile successfuly updated.";
					$_SESSION['error'] = false;
					
					unset($_SESSION['aid']);
					header("Location: index.php?msg=4");
					exit();
				} else {
					$_SESSION['msg'] = "Oops some error occured.";
					$_SESSION['error'] = true;
				}
			}

		} else {
			$_SESSION['msg'] = "Please enter a valid email address.";
			$_SESSION['error'] = true;
		}
	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
	}
}

$query = $db->query_first("SELECT * FROM admin WHERE id={$_SESSION['aid']}");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link id="bootstrap-style" href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
	<!--[if lt IE 7 ]>
		<link id="ie-style" href="css/style-ie.css" rel="stylesheet">
	<![endif]-->
	<!--[if IE 8 ]>
		<link id="ie-style" href="css/style-ie.css" rel="stylesheet">
	<![endif]-->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="img/favicon.ico">
</head>
<body>
	<?php include("include/header.php"); ?>
		<div class="container-fluid">
            <div class="row-fluid">
                <?php include("include/sidebar.php"); ?>
                <?php include("include/no-javascript-error.php"); ?>
                <div id="content" class="span10">
                <!-- start: Content -->
                <?php include("include/breadcrumbs.php"); ?>
                    
                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-user"></i><span class="break"></span>Profile</h2>
                            </div>
                            
                            
                            <?php if(isset($_SESSION['msg'])) { ?>
                            <div class="box-content alerts">
                                <div class="alert alert-<?php echo $_SESSION['error']==true ? 'error':'success'; ?>">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <?php echo $_SESSION['msg']; ?>
                                </div>
                            </div><!--alerts-->
                            <?php } // if isset($_SESSION['msg']) ?>
                            
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="name">* Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text" value="<?php echo $query['name']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="email">* Email</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="email" name="email" type="text" value="<?php echo $query['email']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="username">Username</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo $query['username']; ?></span>
                                        </div>
                                      </div>
                                      
                                      <div class="control-group">
                                        <label class="control-label" for="old_password">Old Password</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="old_password" name="old_password" type="password" value="" />
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="new_password">New Password</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="new_password" name="new_password" type="password" value="" />
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="new_password2">Retype Password</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="new_password2" name="new_password2" type="password" value="" />
                                        </div>
                                      </div>
                                      
                                      <div class="control-group">
                                        <label class="control-label">Join Date</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo date("M d, Y h:ia",strtotime($query['date'])); ?></span>
                                        </div>
                                      </div>
                                      <div class="form-actions">
                                        <button id="submit" name="submit" type="submit" class="btn btn-primary">Save changes</button>
                                        <button class="btn" id="cancelButton">Cancel</button>
                                      </div>
                                    </fieldset>
                                  </form>
                            
                            </div>
                        </div><!--/span-->
                    
                    </div><!--/row-->
                    
                    
                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
		<div class="clearfix">&nbsp;</div>
		<?php include("include/footer.php"); ?>		
	</div><!--/.fluid-container-->
<?php include("include/footer-inc.php"); ?>
<?php unset($_SESSION['msg'], $_SESSION['error']); ?>
</body>
</html>
