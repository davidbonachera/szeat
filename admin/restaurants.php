<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'restaurants.php',
					'title'	=> 'Restaurants'
				)
			);
?>
<?php
if (isset($_GET['id']) && checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);

	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM restaurants WHERE id='$id' LIMIT 1")) {
			$_SESSION['msg'] = "Restaurant successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: restaurants.php");
			exit();
		} else {
			$_SESSION['msg'] = "Restaurant can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: restaurants.php");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("restaurants", $data, "id='$id'")) {
			$_SESSION['msg'] = "Restaurant status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: restaurants.php");
			exit();
		} else {
			$_SESSION['msg'] = "Restaurant status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: restaurants.php");
			exit();
		}
	} elseif (isset($_GET['black_list'])) {
		
		$data['black_list'] = $_GET['black_list'];
		
		if ($db->query_update("restaurants", $data, "id='$id'")) {
			$_SESSION['msg'] = "Restaurant status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: restaurants.php");
			exit();
		} else {
			$_SESSION['msg'] = "Restaurant status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: restaurants.php");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Restaurants</title>
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
                                <h2><i class="icon-list-alt"></i><span class="break"></span>Restaurants</h2>
                                <div class="box-icon">
                                    <a href="restaurants-add.php" class="btn-link noUnderline"><i class="fa-icon-plus"> Add New Restaurant</i></a>
                                </div>
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
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                  <thead>
                                      <tr>
                                          <th>Restaurant Name</th>
                                          <th>Image</th>
                                          <th>Address</th>
                                          <th>Comission</th>
                                          <th>Orders</th>
                                          <th>Blacklisted</th>
                                          <th>Status</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM restaurants ORDER BY id"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo $r['name']; ?></td>
                                                <td><img src="../<?php echo $r['thumbnail']; ?>" width="100" /></td>
                                                <td><?php echo $r['address']; ?></td>
                                                <td><?php echo $r['comission_type']=='fixed' ? '$'.$r['comission_value']:$r['comission_value'].'%'; ?></td>
                                                <td><?php echo recordsCounter('orders','restaurant_id',$r['id']); ?></td>
                                                <td class="center">
                                                	<span class="label <?php echo $r['black_list']==1 ? 'label-important' : 'label-success'; ?>">
                                                    	<?php echo $r['black_list']==1 ? 'Blacklisted' : 'Active'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status']==1 ? 'label-success' : NULL; ?>">
                                                    	<?php echo $r['status']==1 ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <a class="btn btn-info" href="restaurants-edit.php?id=<?php echo $r['id']; ?>" title="Edit">
                                                        <i class="icon-edit icon-white"></i>  
                                                    </a>
                                                    <a class="btn btn-inverse" href="?id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>
                                                    <a class="btn <?php echo $r['black_list']==0 ? 'btn-success':NULL; ?>" href="?id=<?php echo $r["id"]; ?>&black_list=<?php echo $r['black_list']==1 ? 0:1; ?>" title="Blacklist">
                                                        <i class="<?php echo $r['black_list']==0 ? 'icon-ok-circle icon-white':'icon-ban-circle'; ?>"></i> 
                                                    </a>
                                                    <a class="btn btn-danger" href="?id=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                        <i class="icon-trash icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-primary" href="menus.php?id=<?php echo $r['id']; ?>" title="Menus">
                                                        <i class="icon-glass icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-warning" href="restaurants-add-delivery-hours.php?id=<?php echo $r['id']; ?>" title="Delivery Hours">
                                                        <i class="icon-time icon-white"></i> 
                                                    </a>
                                                    <!--
                                                    <a href="restaurants-add-delivery-hours.php?id=<?php echo $r['id']; ?>" class="label">Delivery Hours</a>
                                                    <a href="menus.php?id=<?php echo $r['id']; ?>" class="label"> <i class="fa-icon-glass icon-white"></i>Menus</a>
                                                    -->
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
