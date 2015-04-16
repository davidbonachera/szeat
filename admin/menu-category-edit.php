<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$id = addslashes($_GET['id']);
	$cid = addslashes($_GET['c_id']);
?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'restaurants.php',
					'title'	=> 'Restaurants'
				),
				array(
					'link'	=> 'menus.php?id='.$id,
					'title'	=> 'Categories &amp; Menu Items'
				),
				array(
					'link'	=> '#',
					'title'	=> 'Edit Category'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "Restaurant Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: restaurants.php");
	exit();
}
if (!checkFeild($cid)) {
	$_SESSION['msg'] 	= "Category Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: menus.php?id=$id");
	exit();
}
if ($_POST) {
	if (checkFeild($_POST['title'])) {
		
		$data['restaurant_id'] 	= $id;
		$data['title'] 			= ucwords(strtolower($_POST['title']));
		$data['description'] 	= htmlentities($_POST['description']);
		$data['prior']			= is_numeric($_POST['prior']) ? $_POST['prior']:0;

		if ($db->query_update("menu_categories", $data, "id='$cid'")) {
			$_SESSION['error'] = false;
			$_SESSION['msg'] = "Category successfully edited";
			header("Location: menus.php?id=$id");
			exit();
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg'] = "Category couldn't be edited";
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg'] = "Please enter category title.";
	}
}

$query = $db->query_first("SELECT * FROM menu_categories WHERE id='$cid'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit Menu Category</title>
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
                    
                    <?php if(isset($_SESSION['msg'])) { ?>
                    <div class="box-content alerts">
                        <div class="alert alert-<?php echo $_SESSION['error']==true ? 'error':'success'; ?>">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <?php echo $_SESSION['msg']; ?>
                        </div>
                    </div><!--alerts-->
                    <?php } // if isset($_SESSION['msg']) ?>
                    
                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-glass"></i><span class="break"></span>Edit Menu Category</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                	<input type="hidden" name="form" id="form" value="category" />
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="title">* Title</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="title" name="title" type="text" value="<?php echo $query['title']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="description">Description</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="description" name="description" rows="3"><?php echo $query['description']; ?></textarea>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="prior">* Sort Order</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="prior" name="prior" type="text" value="<?php echo $query['prior']; ?>">
                                          <p class="smallFont">Only numeric values are allowed. Lowest number will show category on top.</p>
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
