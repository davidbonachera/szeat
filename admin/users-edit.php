<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $id = addslashes($_GET['id']); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'users.php',
					'title'	=> 'Users'
				),
				array(
					'link'	=> 'users-edit.php?id='.$id,
					'title'	=> 'Edit User'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "User not found.";
	$_SESSION['error'] 	= false;
	header("Location: users.php");
	exit();
}

if ($_POST) {
	if (checkFeild($_POST["name"]) && checkFeild($_POST['email'])) {
		if(validEmail(trim($_POST["email"]))) {
			
			$data["area_id"] 		= $_POST["area_id"];
			$data["building_id"] 	= $_POST["building_id"];
			$data["apartment"] 		= $_POST["apartment"];
			$data["name"] 			= $_POST["name"];
			$data["email"] 			= $_POST["email"];
			$data["phone"] 			= $_POST["phone"];
			$data["newsletter"] 	= $_POST["newsletter"];
			$data["status"] 		= $_POST["status"];
			
			if (checkFeild($_POST['new_password'])) {
				if ($_POST['new_password']==$_POST['new_password2']) {
					$_SESSION['error'] = false;
					$data['password'] = md5($_POST['new_password']);
				} else {
					$_SESSION['msg'] = "Oops, your passwords don't match.";
					$_SESSION['error'] = true;
				}
			} else {
				$_SESSION['error'] = false;
			}
	
			if ($_SESSION['error']==false) {
				$db->query_update("users", $data, "id='$id'");
				if($db->affected_rows > 0){
					$_SESSION['msg'] = "User successfully updated.";
					$_SESSION['error'] = false;
					header("Location: users-edit.php?id=$id");
					exit();
				} else {
					$_SESSION['msg'] = "<strong>Oops!</strong> User wasn't updated.";
					$_SESSION['error'] = true;
					header("Location: users-edit.php?id=$id");
					exit();
				}
			}
			
		} else {
			$_SESSION['msg'] = "Please enter a valid email address.";
			$_SESSION['error'] = true;
		}

	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
		header("Location: users-edit.php?id=$id");
		exit();
	}
}

$query = $db->query_first("SELECT * FROM users WHERE id='$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit User</title>
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
                                <h2><i class="icon-edit"></i><span class="break"></span>Edit User</h2>
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
                                        <label class="control-label" for="phone">* Phone</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="phone" name="phone" type="text" value="<?php echo $query['phone']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="area_id">* Area</label>
                                        <div class="controls">
                                          <select id="area_id" name="area_id" data-rel="chosen">
                                            <?php $areas = $db->query("SELECT * FROM areas ORDER BY title ASC"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while($ar=$db->fetch_array($areas)) { ?>
                                                	<option value="<?php echo $ar['id']; ?>" <?php echo $query['area_id']==$ar['id'] ? 'selected':NULL; ?>>
														<?php echo $ar['title']; ?>
                                                    </option>
                                                <?php } // endwhile $areas ?>
                                            <?php } // $db->affected_rows ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="building_id">* Building</label>
                                        <div class="controls">
                                          <select id="building_id" name="building_id" data-rel="chosen">
                                            <?php $buildings = $db->query("SELECT * FROM buildings ORDER BY title ASC"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while($br=$db->fetch_array($buildings)) { ?>
                                                	<option value="<?php echo $br['id']; ?>" <?php echo $query['building_id']==$br['id'] ? 'selected':NULL; ?>>
														<?php echo $br['title']; ?>
                                                    </option>
                                                <?php } // endwhile $areas ?>
                                            <?php } // $db->affected_rows ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="apartment">* Address</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="apartment" name="apartment" type="text" value="<?php echo $query['apartment']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="date">Created Date</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo date("d-m-y",strtotime($query['date'])); ?></span>
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
                                        <label class="control-label">Send Newsletter</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="newsletter" id="newsletter1" value="1" <?php echo $query['newsletter']==1 ? 'checked':NULL; ?>>
                                            Yes
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="newsletter" id="newsletter2" value="0" <?php echo $query['newsletter']==0 ? 'checked':NULL; ?>>
                                            No
                                          </label>
                                        </div>
                                      </div>
                                      
                                      <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="status" id="status1" value="1" <?php echo $query['status']==1 ? 'checked':NULL; ?>>
                                            Enable
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="status" id="status2" value="0" <?php echo $query['status']==0 ? 'checked':NULL; ?>>
                                            Disable
                                          </label>
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
