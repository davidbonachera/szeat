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
					'link'	=> 'smtp.php',
					'title'	=> 'SMTP Settings'
				)
			);
?>
<?php
if($_POST) {
	if (checkFeild($_POST["server"]) && checkFeild($_POST["port"]) && checkFeild($_POST["username"]) && checkFeild($_POST["password"])) {

		$data['server'] 	= $db->escape($_POST['server']);
		$data['port'] 		= $db->escape($_POST['port']);
		$data['ssl'] 		= $db->escape($_POST['ssl']);
		$data['username'] 	= $db->escape($_POST['username']);
		$data['password'] 	= $db->escape($_POST['password']);

		if($db->query_update("smtp", $data, "id=1")) {
			$_SESSION['msg'] = "SMTP Settings Updated.";
			$_SESSION['error'] = false;
		} else {
			$_SESSION['msg'] = "Oops some error occured.";
			$_SESSION['error'] = true;
		}

	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
	}
}

$site = $db->query_first("SELECT * FROM smtp WHERE id=1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SMTP Settings</title>
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
                                <h2><i class="icon-envelop"></i><span class="break"></span>SMTP Settings</h2>
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
                                        <label class="control-label" for="server">* Server</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="server" name="server" type="text" value="<?php echo $site['server']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="port">* Port</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="port" name="port" type="text" value="<?php echo $site['port']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label for="ssl" class="control-label">* Encryption</label>
                                        <div class="controls">
                                          <select id="ssl" name="ssl">
                                            <option value="1" <?php echo $site['ssl']==1 ? 'selected':NULL; ?>>SSL/TLS</option>
                                            <option value="0" <?php echo $site['ssl']==0 ? 'selected':NULL; ?>>Non-SSL</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="username">* Username</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="username" name="username" type="text" value="<?php echo $site['username']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="password">* Password</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="password" name="password" type="password" value="<?php echo $site['password']; ?>">
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
