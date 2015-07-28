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
					'link'	=> 'settings.php',
					'title'	=> 'Settings'
				)
			);
?>
<?php
if($_POST) {
	if (checkFeild($_POST["title"]) && checkFeild($_POST["email"])) {
		if(validEmail(trim($_POST["email"]))) {
			
			$data['title'] 			= htmlentities($_POST['title']);
			$data['tagline'] 		= htmlentities($_POST['tagline']);
			$data['email'] 			= $_POST['email'];
			$data['facebook'] 		= $_POST['facebook'];
			$data['twitter'] 		= $_POST['twitter'];
			$data['currency'] 		= $_POST['currency'];
			$data['bank_details'] 	= $_POST['bank_details'];
			$data['address'] 		= $_POST['address'];
			$data['tele'] 			= $_POST['tele'];

			if($db->query_update("config", $data, "id=1")) {
				$_SESSION['msg'] = "Site Configuration Updated.";
				$_SESSION['error'] = false;
			} else {
				$_SESSION['msg'] = "Oops some error occured.";
				$_SESSION['error'] = true;
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

$site = $db->query_first("SELECT * FROM config WHERE id=1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Settings</title>
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
                                <h2><i class="icon-cog"></i><span class="break"></span>Settings</h2>
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
                                        <label class="control-label" for="title">* Title</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="title" name="title" type="text" value="<?php echo $site['title']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="tagline">Tagline</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="tagline" name="tagline" type="text" value="<?php echo $site['tagline']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="email">* Email</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="email" name="email" type="text" value="<?php echo $site['email']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="facebook">Facebook</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="facebook" name="facebook" type="text" value="<?php echo $site['facebook']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="twitter">Twitter</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="twitter" name="twitter" type="text" value="<?php echo $site['twitter']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="currency">Currency Symbol</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="currency" name="currency" type="text" value="<?php echo $site['currency']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="bank_details">Bank Details</label>
                                          <div class="controls">
                                            <textarea class="" id="bank_details" name="bank_details" rows="3" style="width:270px;"><?php echo $site['bank_details']; ?></textarea>
                                          </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="address">Address</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="address" name="address" type="text" value="<?php echo $site['address']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="tele">Telephone</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="tele" name="tele" type="text" value="<?php echo $site['tele']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label">Start Date</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo date("M d, Y h:ia",strtotime($site['date'])); ?></span>
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
