<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$id = addslashes($_GET['id']);
	$mid = addslashes($_GET['m_id']);
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
					'title'	=> 'Edit Item'
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
if (!checkFeild($mid)) {
	$_SESSION['msg'] 	= "Menu Item Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: menus.php?id=$id");
	exit();
}
if ($_POST) {
	if (checkFeild($_POST['menu_cat_id']) || checkFeild($_POST['item_number']) || checkFeild($_POST['name']) || checkFeild($_POST['value']) || checkFeild($_POST['price'])) {
			
		$data['restaurant_id'] 	= $id;
		$data['menu_cat_id'] 	= $_POST['menu_cat_id'];
		$data['item_number'] 	= $_POST['item_number'];
		$data['name'] 			= ucwords(strtolower($_POST['name']));
    $data['name_cn']       = ucwords(strtolower($_POST['name_cn']));
		$data['description'] 	= $_POST['description'];
    $data['description_cn']  = $_POST['description_cn'];
		//$data['value'] 			= $_POST['value'];
		$data['price'] 			= $_POST['price'];
		$data["status"] 		= 1;
		$data["date"] 			= _nowdt;
		
		if ($db->query_update("menu_items", $data, "id='$mid'")) {
			$_SESSION['error'] = false;
			$_SESSION['msg'] = "Menu Item successfully edited";
			header("Location: menus.php?id=$id");
			exit();
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg'] = "Menu Item couldn't be edited";
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg'] = "All * marked fields are required.";
	}
}

$query = $db->query_first("SELECT * FROM menu_items WHERE id='$mid'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit Menu Item</title>
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
                                <h2><i class="icon-glass"></i><span class="break"></span>Edit Menu Item</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                	<input type="hidden" name="form" id="form" value="menu" />
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="menu_cat_id">* Category</label>
                                        <div class="controls">
                                          <select id="menu_cat_id" name="menu_cat_id" data-rel="chosen">
                                            <?php $areas = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title ASC"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while($ar=$db->fetch_array($areas)) { ?>
                                                	<option value="<?php echo $ar['id']; ?>" <?php echo $query['menu_cat_id']==$ar['id'] ? 'selected':NULL; ?>>
														<?php echo $ar['title']; ?>
                                                    </option>
                                                <?php } // endwhile $areas ?>
                                            <?php } // $db->affected_rows ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="item_number">* Item Number</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="item_number" name="item_number" type="text" value="<?php echo $query['item_number']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="name">* English Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text" value="<?php echo $query['name']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="name_cn">Chinese Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="name_cn" name="name_cn" type="text" value="<?php echo $query['name_cn']; ?>">
                                        </div>
                                      </div>                                      
                                      <div class="control-group">
                                        <label class="control-label" for="description">English Description</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="description" name="description" rows="3"><?php echo $query['description']; ?></textarea>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="description_cn">Chinese Description</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="description_cn" name="description_cn" rows="3"><?php echo $query['description_cn']; ?></textarea>
                                        </div>
                                      </div>                                      
                                      <!--
                                      <div class="control-group">
                                        <label class="control-label" for="value">* Quantity</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="value" name="value" type="text" value="">
                                        </div>
                                      </div>
                                      -->
                                      <div class="control-group">
                                        <label class="control-label" for="price">* Price</label>
                                        <div class="controls">
                                          <div class="input-prepend">
                                            <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="<?php echo $query['price']; ?>">
                                          </div>
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
