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
					'link'	=> 'cuisines.php',
					'title'	=> 'Cuisines'
				)
			);
?>
<?php
if (isset($_GET['id']) && checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);

	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM cuisines WHERE id='$id' LIMIT 1")) {
			$_SESSION['msg'] = "Cuisine successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: cuisines.php");
			exit();
		} else {
			$_SESSION['msg'] = "Cuisine can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: cuisines.php");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("cuisines", $data, "id='$id'")) {
			$_SESSION['msg'] = "Cuisine status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: cuisines.php");
			exit();
		} else {
			$_SESSION['msg'] = "Cuisine status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: cuisines.php");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cuisines</title>
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
                                <h2><i class="icon-fire"></i><span class="break"></span>Cuisines</h2>
                                <div class="box-icon">
                                    <a href="cuisines-add.php" class="btn-link noUnderline"><i class="fa-icon-plus"> Add New Cuisine</i></a>
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
                                          <th>Cuisine Type</th>
                                          <th>Restaurants</th>
                                          <th>Created</th>
                                          <th>Status</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM cuisines ORDER BY id"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo $r['title']; ?></td>
                                                <td><?php echo recordsCounter('restaurants_cuisines','cuisine_id',$r['id']); ?></td>
                                                <td class="center"><?php echo date("Y/m/d",strtotime($r['date'])); ?></td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status']==1 ? 'label-success':NULL; ?>">
                                                    	<?php echo $r['status']==1 ? 'Active':'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <a class="btn btn-info" href="cuisines-edit.php?id=<?php echo $r['id']; ?>" title="Edit">
                                                        <i class="icon-edit icon-white"></i>  
                                                    </a>
                                                    <a class="btn btn-inverse" href="?id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-danger" href="?id=<?php echo $r["id"]; ?>&delete" title="Delete">
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
