<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/class.phpmailer.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $currencySymbol = _priceSymbol; ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'orders.php',
					'title'	=> 'Orders'
				)
			);
?>
<?php
if (isset($_GET['id']) && checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);

	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM orders WHERE id='$id' LIMIT 1")) {
			$_SESSION['msg'] = "Order successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: orders.php");
			exit();
		} else {
			$_SESSION['msg'] = "Order can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: orders.php");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $db->escape($_GET['status']);
		
		if ($db->query_update("orders", $data, "id='$id'")) {
			$_SESSION['msg'] = "Order status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: orders.php");
			exit();
		} else {
			$_SESSION['msg'] = "Order status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: orders.php");
			exit();
		}
	} elseif (isset($_GET['sent'])) {
		
		$data['sent'] 		= $db->escape($_GET['sent']);
		$data['admin_id'] 	= $db->escape($_SESSION['aid']);
		
		if ($db->query_update("orders", $data, "id='$id'")) {
			
			if ($data['sent']==1) {
				$order = $db->query_first("SELECT * FROM orders WHERE id={$id}");
			
				$siteTitle 	= _title;
				$fromEmail 	= _email;
				$toEmail 	= getData("users","email",$order['user_id']);
				$toName 	= getData("users","name",$order['user_id']);
				$resName	= getData("restaurants","name",$order['restaurant_id']);
				$orderID	= $order['id'];
				$oPrice		= $order['price'];
				$oNotes		= $order['notes'];
				
				$itemsTable = "<table>";
				$items = $db->query("SELECT * FROM order_items WHERE order_id='$orderID'");
				while ($item = $db->fetch_array($items)) {
					$menuItem = $db->query_first("SELECT * FROM menu_items WHERE id={$item['menu_item_id']}");
					if ($db->affected_rows > 0) {
						if ($item['menu_item_size'] > 0) {
							$menuSize = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['menu_item_size']}");
							$itemValue = $menuSize['value'];
							$itemPrice = number_format($menuSize['price']*$item['quantity'],2);
						} else {
							$itemValue = $menuItem['value'];
							$itemPrice = number_format($menuItem['price']*$item['quantity'],2);
						}
						$itemsTable .= "<tr>
											<td>{$item['quantity']}x - {$menuItem['item_number']}</td>
											<td>{$menuItem['name']} $itemValue</td>
											<td>$itemPrice</td>
										 </tr>
										";
					}
					
				} // end foreach $items loop
				$itemsTable .= "</table>";
				
				$template	= $db->query_first("SELECT * FROM email_templates WHERE name='order_sent' LIMIT 1");
					
				$from_name	= $template['from_name'];
				$from_email	= $template['from_email'];
				
				if ($lang=='cn') {
					$mSubject = $template['subject_cn'];
				} else {
					$mSubject = $template['subject'];
				}
 				
				$mSubject = str_replace("#NAME#", 			$toName, 	$mSubject);
				$mSubject = str_replace("#RESTAURANT#", 	$resName, 	$mSubject);
				$mSubject = str_replace("#ORDERNO#", 		$orderID, 	$mSubject);
				$mSubject = str_replace("#ITEMDETAILS#", 	$itemsTable,$mSubject);
				$mSubject = str_replace("#TOTALPRICE#", 	$oPrice, 	$mSubject);
				$mSubject = str_replace("#NOTES#", 			$oNotes, 	$mSubject);
		
				
				if ($lang=='cn') {
					$mBody = html_entity_decode($template['body_cn']);	
				} else {
					$mBody = html_entity_decode($template['body']);	
				}

				$mBody = str_replace("#NAME#", 			$toName, 	$mBody);
				$mBody = str_replace("#RESTAURANT#", 	$resName, 	$mBody);
				$mBody = str_replace("#ORDERNO#", 		$orderID, 	$mBody);
				$mBody = str_replace("#ITEMDETAILS#", 	$itemsTable,$mBody);
				$mBody = str_replace("#NOTES#", 		$oNotes, 	$mBody);
				$mBody = str_replace("#LINK#", 			$linkToRate,$mBody);
				$mBody = str_replace("#TOTALPRICE#", 	number_format($oPrice,2), $mBody);
	
				sendEmail($from_name, $from_email, $toEmail, $mSubject, $mBody);
			}

			$_SESSION['msg'] = "Order status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: orders.php");
			exit();
		} else {
			$_SESSION['msg'] = "Order status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: orders.php");
			exit();
		}
	} elseif (isset($_GET['paid'])) {
		
		$data['paid'] 		= $db->escape($_GET['paid']);
		$data['admin_id'] 	= $db->escape($_SESSION['aid']);
		
		if ($db->query_update("orders", $data, "id='$id'")) {
			$_SESSION['msg'] = "Order status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: orders.php");
			exit();
		} else {
			$_SESSION['msg'] = "Order status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: orders.php");
			exit();
		}
	}
}
/*
$check 		= $db->query("SELECT * FROM orders WHERE viewed=0");
$newOrders 	= $db->affected_rows;
if ($newOrders > 0) {
	$data['viewed'] = 1;
	$viewed = $db->query_update("orders",$data);
}
*/

$check 		= $db->query("SELECT * FROM orders WHERE sent=0");
$newOrders 	= $db->affected_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Orders</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if (!isset($_GET['type']) || $_GET['type']=="") { ?>
	<meta http-equiv="refresh" content="30">
<?php } ?>
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
    <style type="text/css">
		#overlay {display:none !important;}
	</style>
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
                                <h2><i class="icon-shopping-cart"></i><span class="break"></span>Orders</h2>
                            </div>
                            
                            <style type="text/css">
								.noMarginBottom form,
								.noMarginBottom form .span4 label,
								.noMarginBottom form .span4 select {
									margin-bottom:0;
								}
							</style>
                            <div class="box-content noMarginBottom">
                              <form method="get">
                                  <div class="span4">
                                    <div>
                                    	<label> Order Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <select name="type" class="input-medium">
                                                <option value="0" <?php echo isset($_GET['type']) 	&& $_GET['type']=="0" ? 'selected':NULL; ?>>All</option>
                                                <option value="1" <?php echo isset($_GET['type']) 	&& $_GET['type']=="1" ? 'selected':NULL; ?>>Sent</option>
                                                <option value=""  <?php echo (!isset($_GET['type']) || $_GET['type']=="") ? 'selected':NULL; ?>>Not Sent</option>
                                            </select>
                                        </label>
                                    </div>
                                  </div>
                                  <div class="span2">
                                    <div>
                                    	<button type="submit" class="btn btn-primary">Filter</button>
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
                                <table class="table table-striped table-bordered bootstrap-datatable" id="orders">
                                  <thead>
                                      <tr>
                                          <th class="sorting_desc hidden"></th>
                                          <th>Received</th>
                                          <th>Restaurant (EN)</th>
                                          <th>Restaurant (CN)</th>
                                          <th>User</th>
                                          <th>Price</th>
                                          <th>Sent</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php 
										if (!isset($_GET['type']) || $_GET['type']=="") {
											$query = $db->query("SELECT * FROM orders WHERE sent=0 ORDER BY date DESC, id DESC"); 
										} elseif (isset($_GET['type']) && $_GET['type']=="1") {
											$query = $db->query("SELECT * FROM orders WHERE sent=1 ORDER BY date DESC, id DESC"); 
										} elseif (isset($_GET['type']) && $_GET['type']=="0") {
											$query = $db->query("SELECT * FROM orders ORDER BY date DESC, id DESC"); 
										}
									?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                            	<td class="hidden"><?php echo $r['id']; ?></td>
                                                <td><?php echo date("H:i d-m-y",strtotime($r['date'])); ?></td>
                                                <td><?php echo getData('restaurants','name',$r['restaurant_id']); ?></td>
                                                <td><?php echo getData('restaurants','name_cn',$r['restaurant_id']); ?></td>
                                                <td><?php echo getData('users','name',$r['user_id']); ?></td>
                                                <td><?php echo _priceSymbol; ?><?php echo $r['price']; ?></td>
                                                <td class="center">
                                                    <a class="btn <?php echo $r['sent']==1 ? 'btn-success':NULL; ?>" href="?id=<?php echo $r["id"]; ?>&sent=<?php echo $r['sent']==1 ? 0:1; ?>">
                                                        <i class="<?php echo $r['sent']==1 ? 'icon-ok icon-white':'icon-remove-circle'; ?>"></i> 
                                                    </a>
                                                    <!--
                                                    <br /><br />
                                                    <a class="label <?php echo $r['paid']==1 ? 'label-success':'label-important'; ?>" href="?id=<?php echo $r["id"]; ?>&paid=<?php echo $r['paid']==1 ? 0:1; ?>">
														<?php echo $r['paid']==1 ? 'Paid':'Not Paid'; ?>
                                                    </a>
                                                    -->
                                                </td>
                                                <td class="center">
                                                    <a class="btn btn-info" href="orders-edit.php?id=<?php echo $r['id']; ?>" title="View">
                                                        <i class="icon-search icon-white"></i>  
                                                    </a>
                                                    <a class="btn btn-inverse" href="?id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $r['id']; ?>" title="Delete">
                                                        <i class="icon-trash icon-white"></i>
                                                    </a>

                                                    <div class="modal fade" id="deleteModal<?php echo $r['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="col">

                                                                        <p>Are you sure?</p>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                        <a class="btn btn-danger" href="?id=<?php echo $r['id']; ?>&delete" title="Delete">
                                                                            <i class="icon-trash icon-white"></i>
                                                                        </a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

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

<?php if ($newOrders > 0) { ?>
    <audio id="soundAlert" src="notify.wav" preload="auto" autobuffer autoplay></audio>
<?php } // if ($newOrders > 0) ?>

</body>
</html>