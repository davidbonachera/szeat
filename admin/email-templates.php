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
					'link'	=> 'email-templates.php',
					'title'	=> 'Templates'
				)
			);
?>
<?php
if (isset($_GET['id']) && checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);
	
	if (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("email_templates", $data, "id='$id'")) {
			$_SESSION['msg'] = "Template status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: email-templates.php");
			exit();
		} else {
			$_SESSION['msg'] = "Template status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: email-templates.php");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Email Templates</title>
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
                                <h2><i class="icon-envelope"></i><span class="break"></span>Email Templates</h2>
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
                                          <th>Template Name</th>
                                          <th>From</th>
                                          <th>Subject</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM email_templates ORDER BY name"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo ucwords(str_replace("_"," ",$r['name'])); ?></td>
                                                <td><?php echo $r['from_name']; ?><br /><em><?php echo $r['from_email']; ?></em></td>
                                                <td class="center"><?php echo $r['subject']; ?></td>
                                                <td class="center">
                                                    <a class="btn btn-info" href="email-templates-edit.php?id=<?php echo $r['id']; ?>" title="Edit">
                                                        <i class="icon-edit icon-white"></i>  
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
