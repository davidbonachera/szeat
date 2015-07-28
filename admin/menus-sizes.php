<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $id = addslashes($_GET['menu_item']); ?>
<?php $restaurantID = getData("menu_items","restaurant_id",$id); ?>
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
					'link'	=> 'menus.php?id='.$restaurantID,
					'title'	=> 'Categories &amp; Items'
				),
				array(
					'link'	=> 'menus-sizes.php?menu_item='.$id,
					'title'	=> 'Item Sizes'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "Restaurant Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit();
}
if ($_POST) {
	if (checkFeild($_POST['value']) && checkFeild($_POST['price'])) {
		
		$data['restaurant_id'] 	= $restaurantID;
		$data['menu_item_id'] 	= $id;
		$data['value'] 			= $_POST['value'];
		$data['value_cn'] 			= $_POST['value_cn'];
		$data['price'] 			= $_POST['price'];
		$data["status"] 		= 1;
		$data["date"] 			= _nowdt;
		
		if ($db->query_insert("menu_item_sizes",$data)) {
			$_SESSION['error'] = false;
			$_SESSION['msg'] = "Item Size successfully added";
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg'] = "Item Size couldn't be added";
		}

	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg'] = "All * marked fields are quired.";
	}
}

if (isset($_GET['s_id'])) {
	$dhID = addslashes($_GET['s_id']);
	
	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM menu_item_sizes WHERE id='$dhID' LIMIT 1")) {
			$_SESSION['msg'] = "Size successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		} else {
			$_SESSION['msg'] = "Size can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("menu_item_sizes", $data, "id='$dhID'")) {
			$_SESSION['msg'] = "Size status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		} else {
			$_SESSION['msg'] = "Size status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		}
	}elseif (isset($_GET['status_cn'])) {
		
		$data['status_cn'] = $_GET['status_cn'];
		
		if ($db->query_update("menu_item_sizes", $data, "id='$dhID'")) {
			$_SESSION['msg'] = "Size status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		} else {
			$_SESSION['msg'] = "Size status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: menus-sizes.php?menu_item=$id");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Menu Sizes</title>
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
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Item Sizes</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-down"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                	<input type="hidden" name="form" id="form" value="menu" />
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="value">* English Name / Quantity</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="value" name="value" type="text" value="">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="value_cn">Chinese Name / Quantity</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="value_cn" name="value_cn" type="text" value="">
                                        </div>
                                      </div>                                      
                                      <div class="control-group">
                                        <label class="control-label" for="price">* Price</label>
                                        <div class="controls">
                                          <div class="input-prepend">
                                            <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="">
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
                    
                    <div class="row-fluid sortable">		
                        <div class="span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-leaf"></i><span class="break"></span>Item Sizes</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                                </div>
                            </div>
                            
		                    <div class="box-content">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                  <thead>
                                      <tr>
                                          <th>Menu Item</th>
                                          <th>English Name</th>
                                          <th>Chinese Name</th>
                                          <th>Price</th>
                                          <th>English Status</th>
                                          <th>Chinese Status</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM menu_item_sizes WHERE menu_item_id='$id'"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
                                        <?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo getData('menu_items','name',$r['menu_item_id']); ?></td>
                                                <td><?php echo $r['value']; ?></td>
                                                <td><?php echo $r['value_cn']; ?></td>
                                                <td><?php echo _priceSymbol; ?><?php echo $r['price']; ?></td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status']==1 ? 'label-success' : NULL; ?>">
                                                    	<?php echo $r['status']==1 ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status_cn']==1 ? 'label-success' : NULL; ?>">
                                                    	<?php echo $r['status_cn']==1 ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>                                                
                                                <td class="center">
                                                    <a class="btn btn-inverse" href="?menu_item=<?php echo $id; ?>&s_id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-warning" href="?menu_item=<?php echo $id; ?>&s_id=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status_cn']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>                                                    
                                                    <a class="btn btn-danger" href="?menu_item=<?php echo $id; ?>&s_id=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                        <i class="icon-trash icon-white"></i> 
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } // endwhile $query loop ?>
                                    <?php } // $db->affected_rows ?>
                                  </tbody>
                              </table>            
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
