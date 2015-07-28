<?php require_once("../class/config.inc.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				)
			);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
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
                
                	<div class="sortable row-fluid">
                        <a class="quick-button span2" href="areas.php">
                            <i class="fa-icon-map-marker"></i>
                            <p>Areas</p>
                            <span class="notification green"><?php echo counter('areas',1); ?></span>
                        </a>
                        <a class="quick-button span2" href="buildings.php">
                            <i class="fa-icon-map-marker"></i>
                            <p>Buildings</p>
                            <span class="notification green"><?php echo counter('buildings',1); ?></span>
                        </a>
                        <a class="quick-button span2" href="cuisines.php">
                            <i class="fa-icon-fire"></i>
                            <p>Cuisines</p>
                            <span class="notification green"><?php echo counter('cuisines',1); ?></span>
                        </a>
                        <a class="quick-button span2" href="restaurants.php">
                            <i class="fa-icon-certificate"></i>
                            <p>Restaurants</p>
                            <span class="notification green"><?php echo counter('restaurants',1); ?></span>
                        </a>
                        <a class="quick-button span2" href="orders.php">
                            <i class="fa-icon-shopping-cart"></i>
                            <p>Orders</p>
                            <span class="notification"><?php echo counter('orders',3); ?></span>
                        </a>
                        <a class="quick-button span2" href="users.php">
                            <i class="fa-icon-user"></i>
                            <p>Users</p>
                            <span class="notification green"><?php echo counter('users',1); ?></span>
                        </a>
                    </div>
                    
                    <hr />
                    
                    <div class="sortable row-fluid">
                        <div class="box span12" onTablet="span12" onDesktop="span12">
                            <div class="box-header">
                                <h2><i class="icon-signal"></i><span class="break"></span>Site Statistics</h2>
                            </div>
                            <div class="box-content">
                                <div id="stats-chart"  class="center" style="height:300px" ></div>
                            </div>
                        </div>
                    </div>
                    
                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
			<div class="clearfix">&nbsp;</div>
		<?php include("include/footer.php"); ?>		
	</div><!--/.fluid-container-->
<?php include("include/footer-inc.php"); ?>	
</body>
</html>
