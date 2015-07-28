<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/class.phpmailer.php"); ?>
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
					'link'	=> 'invoicing.php',
					'title'	=> 'Invoicing'
				)
			);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Invoicing</title>
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
                                <h2><i class="icon-file"></i><span class="break"></span>Invoicing</h2>
                            </div>
                            
							<style type="text/css">
								.dateRange {margin-bottom:0;}
								.dateRange .span6 {
									width:33% !important;
								}
								.dateRange .span6.span67 {
									width:20% !important;
									margin-left:0;
								}
								.dateRange .input-xlarge {
									width:150px;
									margin-bottom:0;
								}
							</style>
                            <div class="box-content dateRange alert alert-info">
                              <form method="post">
                                  <div class="span6">
                                    <div>
                                      <label>
                                        Date Range: 
                                        <input type="text" class="input-xlarge datepicker" id="date01" name="date01" placeholder="MM/DD/YY">
                                        <span class="icon-calendar"></span>
                                      </label>
                                    </div>
                                  </div>
                                  <div class="span6">
                                    <div>
                                      <label>
                                        To: 
                                        <input type="text" class="input-xlarge datepicker" id="date02" name="date02" placeholder="MM/DD/YY">
                                        <span class="icon-calendar"></span>
                                      </label>
                                    </div>
                                  </div>
                                  <div class="span6 span67">
                                    <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                      <button type="submit" class="btn">Search</button>
                                    </div>
                                  </div>
                              </form>
                              <div class="clearfix"></div>
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
                                          <th>Items Not Paid</th>
                                          <th>Area</th>
                                          <th>Address</th>
                                          <th>Phone Number</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT r.id AS restaurant_id, r.*, ra.* FROM restaurants AS r LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id WHERE r.id IN (SELECT restaurant_id FROM orders) AND r.status=1 GROUP BY r.id"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo $r['name']; ?></td>
                                                <td><?php echo isset($_POST['date01']) && checkFeild($_POST['date01']) ? itemsNotPaid($r['restaurant_id'],$_POST['date01'],$_POST['date02']):itemsNotPaid($r['restaurant_id']); ?></td>
                                                <td><?php echo getData("areas","title",$r['area_id']); ?></td>
                                                <td><?php echo $r['address']; ?></td>
                                                <td><?php echo $r['phone']; ?></td>
                                                <td class="center">
                                                    <a href="invoicing-view.php?id=<?php echo $r["restaurant_id"]; ?><?php echo isset($_POST['date01']) && checkFeild($_POST['date01']) ? "&start={$_POST['date01']}":NULL; ?><?php echo isset($_POST['date02']) && checkFeild($_POST['date02']) ? "&end={$_POST['date02']}":NULL; ?>" class="btn btn-small btn-info">View</a>
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
