<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/class.phpmailer.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php
if (checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);
	$res = $db->query_first("SELECT * FROM restaurants WHERE id=$id");
} else {
	header("Location: invoicing.php");
	exit;
}
?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'invoicing.php',
					'title'	=> 'Invoicing'
				),
				array(
					'link'	=> "#",
					'title'	=> "{$res['name']} Invoices"
				)
			);
?>
<?php
$queryParas = NULL;
$skip = array("oid","paid","mark");
foreach ($_GET as $key=>$val) {
	if (!in_array($key, $skip)) { 
		$queryParas .= "$key=$val&";
	}
}


if (isset($_GET['mark'])) {
	if ($_GET['mark']=="paid") {
		if (is_array($_POST['invoice'])) {
			$items 				= implode(",", $_POST['invoice']);
			$query 				= $db->query("UPDATE order_items SET paid=1 WHERE id IN ($items)");
			$_SESSION['msg'] 	= "Items have been marked as Paid.";
			$_SESSION['error'] 	= false;
			header("Location: invoicing-view.php?$queryParas");
			exit;
		}
		
	} elseif ($_GET['mark']=="unpaid") {
		if (is_array($_POST['invoice'])) {
			$items 				= implode(",", $_POST['invoice']);
			$query 				= $db->query("UPDATE order_items SET paid=0 WHERE id IN ($items)");
			$_SESSION['msg'] 	= "Items have been marked as Unpaid.";
			$_SESSION['error'] 	= false;
			header("Location: invoicing-view.php?$queryParas");
			exit;
		}
	}
}

if (isset($_GET['oid']) && checkFeild($_GET['oid'])) {
	$oid = addslashes($_GET['oid']);
	if (isset($_GET['paid'])) {
		
		$data['paid'] 		= $_GET['paid'];
		//$data['admin_id'] 	= $_SESSION['aid'];
		
		if ($db->query_update("order_items", $data, "id='$oid'")) {
			$_SESSION['msg'] = "Status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: invoicing-view.php?$queryParas");
			exit();
		} else {
			$_SESSION['msg'] = "Status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: invoicing-view.php?$queryParas");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $res['name']; ?> Invoices</title>
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
                                <h2><i class="icon-file"></i><span class="break"></span><?php echo $res['name']; ?></h2>
                            </div>
                            
							<style type="text/css">
								.dateRange {margin-bottom:0;}
								.dateRange .span6 {
									width:50% !important;
								}
								.dateRange .span6.span167 {
									float:right;
									margin-left:0;
									width:50% !important;
								}
								.dateRange .input-xlarge {
									width:150px;
									margin-bottom:0;
								}
								.dateRange .span6.span167 .btn.btn-round {
									margin-left:15px;
								}
							</style>
                            <div class="box-content dateRange alert alert-info">
                              <div class="span6">
                                <div class="controls">
                                  <form method="post" action="<?php echo "invoicing-view.php?$queryParas"; ?>">
                                      <select id="paid" name="paid" data-rel="chosen" onchange="form.submit()">
                                        <option value="all" <?php echo isset($_POST['paid']) && $_POST['paid']=="all" ? 'selected':NULL; ?>>All</option>
                                        <option value="no" 	<?php echo isset($_POST['paid']) && $_POST['paid']=="no"  ? 'selected':NULL; ?>>Unpaid Items</option>
                                        <option value="1" 	<?php echo isset($_POST['paid']) && $_POST['paid']==1  	  ? 'selected':NULL; ?>>Paid Items</option>
                                      </select>
                                  </form>
                                </div>
                              </div>
                              <div class="span6 span167">
                                <div>
                                  <a href="#" class="btn btn-primary btn-round" id="generateInvoice">Generate Invoice</a>
                                  <a href="#" class="btn btn-success btn-round" id="checkPaid">Mark Paid</a>
                                  <a href="#" class="btn btn-warning btn-round" id="checkUnpaid">Mark Unpaid</a>
                                  <a href="#" class="btn btn-danger btn-round" id="markAll">Check All</a>
                                </div>
                              </div>
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
                                <form method="post" id="invoiceForm" name="invoiceForm" action="<?php echo "invoicing-generate.php?$queryParas"; ?>">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                  <thead>
                                      <tr>
                                          <th>Date</th>
                                          <th>Order #</th>
                                          <th>Menu Item</th>
                                          <th>Price</th>
                                          <th>Customer</th>
                                          <th>Paid / Unpaid</th>
                                          <th>Invoice</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php 
										if (isset($_GET['start']) && $_GET['start']!=NULL) {
											if (!isset($_GET['end']) || $_GET['end']==NULL) $_GET['end'] = date("m/d/Y");
											
											$startDate 	= date("Y-m-d",strtotime($_GET['start']));
											$endDate 	= date("Y-m-d",strtotime($_GET['end']));
											
											$whereDate = "AND DATE(date) BETWEEN '$startDate' AND '$endDate'"; 
										} else {
											$whereDate = NULL;
										}
										if (isset($_POST['paid'])) {
											if ($_POST['paid']=='all') {
												$wherePaid = NULL;
											} else {
												$wherePaid = ($_POST['paid']==1) ? "AND paid='1'":"AND paid='0'";
											}
										} else {
											$wherePaid = NULL;
										}
										$query1 = $db->query("SELECT id FROM orders WHERE restaurant_id=$id $whereDate $wherePaid");
										if ($db->affected_rows > 0) {
											while($r=$db->fetch_array($query1)) {
												$idsArray[] = $r['id'];
											}
											$idsImploded = implode(",", $idsArray);
										} else {
											$idsImploded = "0";
										}
										$query = $db->query("SELECT * FROM order_items WHERE order_id IN ($idsImploded)");
									?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                        	<?php 
												if($r['menu_item_size'] > 0) { 
													$itemNumber = getData('menu_items','item_number',$r['menu_item_id']);
													$itemName 	= getData('menu_items','name',$r['menu_item_id']);
													$itemValue 	= getData('menu_item_sizes','value',$r['menu_item_size']);
													$itemPrice 	= getData('menu_item_sizes','price',$r['menu_item_size']);
													$itemQuantity = $r['quantity'];
												} else {
													$itemNumber = getData('menu_items','item_number',$r['menu_item_id']);
													$itemName 	= getData('menu_items','name',$r['menu_item_id']);
													$itemValue	= NULL;
													$itemPrice 	= getData('menu_items','price',$r['menu_item_id']);
													$itemQuantity = $r['quantity'];
												} 
											?>
                                            <tr>
                                                <td><?php echo date("d-m-y",strtotime($r['date'])); ?></td>
                                                <td><?php echo $r['order_id']; ?></td>
                                                <td><?php echo $itemQuantity." x no. ".$itemNumber." ".$itemName." ".$itemValue; ?></td>
                                                <td>
													<?php echo _priceSymbol; ?> 
													<?php echo number_format($itemPrice*$itemQuantity, 2); ?>
                                                </td>
                                                <td><?php echo getData("users","name",$r['user_id']); ?></td>
                                                <td>
													<span class="label <?php echo $r['paid']==1 ? 'label-success':'label-warning'; ?>"><?php echo $r['paid']==1 ? 'Paid':'Unpaid'; ?></span>
                                                    <!--<a class="label <?php echo $r['paid']==1 ? 'label-success':'label-warning'; ?>" href="<?php echo "invoicing-view.php?$queryParas"; ?>&oid=<?php echo $r["id"]; ?>&paid=<?php echo $r['paid']==1 ? 0:1; ?>" title="<?php echo $r['paid']==1 ? 'Mark as Unpaid':'Mark as Paid'; ?>"><?php echo $r['paid']==1 ? 'Paid':'Unpaid'; ?></a>-->
                                                </td>
                                                <td>
                                                	<label class="checkbox inline">
                                                    	<input class="<?php echo $r['paid']==1 ? 'paid':'unpaid'; ?> markAll" type="checkbox" id="invoice<?php echo $r['id']; ?>" name="invoice[]" value="<?php echo $r['id']; ?>" />
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } // endwhile $query loop ?>
                                    <?php } // $db->affected_rows ?>
                                  </tbody>
                              </table>
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
