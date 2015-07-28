<?php require_once("../class/config.inc.php"); ?>
<?php include("include/functions.php"); ?>
<?php
isset($_SESSION['aid']) ? header("Location: dashboard.php") : FALSE;
isset($_GET['msg']) 	? $msg = $db->escape($_GET['msg'])  : FALSE;
$_POST 					? $err = login() : FALSE;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title><?php echo _title; ?> - Admin</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- end: Meta -->
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	<!-- start: CSS -->
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
	<style type="text/css">
		body { background: url(img/bg-login.jpg) !important; }
	</style>
</head>

<body>
	<div class="container-fluid">
		<div class="row-fluid">
            
			<div class="row-fluid">
            	
				<div class="login-box">
                	<br />
                    <center><a href="../"><img src="../img/logo_new2.png" alt="" /></a></center>
                    <br />
					<!--
                    <div class="icons">
						<a href="../"><i class="icon-home"></i></a>
					</div>
					<h2>Login to your account</h2>
                    -->
					<form class="form-horizontal" action="index.php" method="post">
						<fieldset>
							
							<div class="input-prepend" title="Username">
								<span class="add-on"><i class="icon-user"></i></span>
								<input class="input-large span10" name="username" id="username" type="text" placeholder="type username"/>
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password">
								<span class="add-on"><i class="icon-lock"></i></span>
								<input class="input-large span10" name="password" id="password" type="password" placeholder="type password"/>
							</div>
							<div class="clearfix"></div>
							
							<div class="button-login">	
								<button type="submit" class="btn btn-primary"><i class="icon-off icon-white"></i> Login</button>
							</div>
							<div class="clearfix"></div>
					</form>
				</div><!--/span-->
                
				<?php if (isset($msg)) { ?>
                    <?php if ($msg == 1) { ?>
                        <div class="alert alert-error" style="width:350px; margin:0 auto;">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Oh snap!</strong> username or password was incorrect.
                        </div>
                    <?php } elseif ($msg==2) { ?>
                        <div class="alert alert-info" style="width:350px; margin:0 auto;">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Heads up!</strong> You have logged out succesfully.
                        </div>
                    <?php } elseif ($msg==3) { ?>
                        <div class="alert alert-block" style="width:350px; margin:0 auto;">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Warning!</strong> Session expired or you did not login.
                        </div>
                    <?php } elseif ($msg==4) { ?>
                        <div class="alert alert-success" style="width:350px; margin:0 auto;">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Your password has been changed!</strong> 
                            <br />please login with new password to continue.
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if (isset($err)) { ?>
                    <div class="alert alert-error" style="width:350px; margin:0 auto;">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $err['error']; ?>
                    </div>
                <?php } ?>
				
			</div><!--/row-->
			
		</div><!--/fluid-row-->
				
	</div><!--/.fluid-container-->
    
	<!-- start: JavaScript-->    
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/jquery-ui-1.8.21.custom.min.js"></script>    
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src='js/fullcalendar.min.js'></script>
    <script src='js/jquery.dataTables.min.js'></script>
    <script src="js/excanvas.js"></script>
	<script src="js/jquery.flot.min.js"></script>
	<script src="js/jquery.flot.pie.min.js"></script>
	<script src="js/jquery.flot.stack.js"></script>
	<script src="js/jquery.flot.resize.min.js"></script>
	<script src="js/jquery.chosen.min.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/jquery.cleditor.min.js"></script>
    <script src="js/jquery.noty.js"></script>
    <script src="js/jquery.elfinder.min.js"></script>
    <script src="js/jquery.raty.min.js"></script>
    <script src="js/jquery.iphone.toggle.js"></script>
    <script src="js/jquery.uploadify-3.1.min.js"></script>
    <script src="js/jquery.gritter.min.js"></script>
    <script src="js/jquery.imagesloaded.js"></script>
    <script src="js/jquery.masonry.min.js"></script>
    <script src="js/jquery.knob.js"></script>
    <script src="js/jquery.sparkline.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- end: JavaScript-->
</body>
</html>