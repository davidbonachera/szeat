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
					'link'	=> 'orders.php',
					'title'	=> 'orders'
				),
				array(
					'link'	=> 'orders-edit.php?id='.$id,
					'title'	=> 'Edit Order'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "Order Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: orders.php");
	exit();
}

if ($_POST) {
	if (checkFeild($_POST["price"]) && checkFeild($_POST['notes'])) {

		$data["price"] 		= $_POST["price"];
		$data["sent"] 		= $_POST["sent"];
		$data["notes"] 		= $_POST["notes"];
		$data["status"] 	= $_POST["status"];
	
		$db->query_update("orders", $data, "id='$id'");
		if($db->affected_rows > 0){
			$_SESSION['msg'] = "Order successfully updated.";
			$_SESSION['error'] = false;
			header("Location: orders-edit.php?id=$id");
			exit();
		} else {
			$_SESSION['msg'] = "<strong>Oops!</strong> Order wasn't updated.";
			$_SESSION['error'] = true;
			header("Location: orders-edit.php?id=$id");
			exit();
		}
	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
		header("Location: orders-edit.php?id=$id");
		exit();
	}
}

$query = $db->query_first("SELECT * FROM orders WHERE id='$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit Order</title>
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
                                <h2><i class="icon-edit"></i><span class="break"></span>Edit Order</h2>
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
                                        <label class="control-label" for="user_id">User</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('users','name',$query['user_id']); ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Restaurant</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','name',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="price">* Price</label>
                                        <div class="controls">
                                          <div class="input-prepend">
                                            <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="<?php echo $query['price']; ?>">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label">Sent to Restaurant</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="sent" id="sent1" value="1" <?php echo $query['sent']==1 ? 'checked':NULL; ?>>
                                            Yes
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="sent" id="sent2" value="0" <?php echo $query['sent']==0 ? 'checked':NULL; ?>>
                                            No
                                          </label>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="admin_id">Admin</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo checkFeild($query['admin_id']) ? getData('admin','name',$query['admin_id']):'N/A'; ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="notes">* Notes</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="notes" name="notes" rows="3"><?php echo $query['notes']; ?></textarea>
                                          <p class="help-block">Visible only to Administrators</p>
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
