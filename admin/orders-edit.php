<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/class.phpmailer.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $id = addslashes($_GET['id']); ?>
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
				),
				array(
					'link'	=> 'orders-edit.php?id='.$id,
					'title'	=> 'View Order'
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
	$data["sent"] 		= $_POST["sent"];
	$data["paid"] 		= $_POST["paid"];

	$pre 	= $db->query_first("SELECT * FROM orders WHERE id={$id}");
	$update = $db->query_update("orders", $data, "id='$id'");
	if($db->affected_rows > 0){
		
		if ($_POST['sent']==1 && $pre['sent']==0) {
			
			$order 	= $db->query_first("SELECT * FROM orders WHERE id={$id}");
			
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
//echo "<pre>";print_r($from_email);die;
			$mBody = str_replace("#NAME#", 			$toName, 	$mBody);
			$mBody = str_replace("#RESTAURANT#", 	$resName, 	$mBody);
			$mBody = str_replace("#ORDERNO#", 		$orderID, 	$mBody);
			$mBody = str_replace("#ITEMDETAILS#", 	$itemsTable,$mBody);
			$mBody = str_replace("#NOTES#", 		$oNotes, 	$mBody);
			$mBody = str_replace("#LINK#", 			$linkToRate,$mBody);
			$mBody = str_replace("#TOTALPRICE#", 	number_format($oPrice,2), $mBody);
			
			$to = $toEmail;
			//echo $to;die;
			$subject = $mSubject;
			$txt = $mBody;

$headers = 'From: Shenzhen Eat<admin@shenzheneat.com>' . "\r\n";
    $headers .="Reply-To:admin@shenzheneat.com \r\n" ;
    $headers .='X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n"; 


			//$headers  = 'MIME-Version: 1.0' . "\r\n";
			//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			//$headers .= "From: admin@shenzheneat.com \r\n";
			//echo "<pre>";print_r($txt);die;
	
			mail($to,$subject,$txt,$headers);
			sendEmail($from_name, $from_email, $toEmail, $mSubject, $mBody);
		}
		
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
}

$query = $db->query_first("SELECT * FROM orders WHERE id='$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>View Order</title>
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
 <style>
.main_it_cla{width:100%; float:left; padding:0 15px; font-size:12px; margin-bottom:15px;}
.main_it_cla p{border-bottom:1px solid hsl(0, 0%, 40%); margin:7px 0; font-weight:bold;}
.item_f{width:63%; float:left;}
.item_s{width:18%; float:left;}
</style>

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
                                <h2><i class="icon-search"></i><span class="break"></span>View Order</h2>
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
                                        <label class="control-label" for="date">Received</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo date("H:i d-m-y",strtotime($query['date'])); ?></span>
                                        </div>
                                      </div>
                                      
                                      <h3>Restaurant Information</h3>
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Name (EN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','name',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Address (EN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','address',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>                                         
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Name (CN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','name_cn',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>  
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Address (CN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','address_cn',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>                                                                           
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Phone</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('restaurants','phone',$query['restaurant_id']); ?></span>
                                        </div>
                                      </div>
                                  
                                      
                                      <h3>Order Details</h3>
                                      <table cellpadding="5" style="margin-left:105px">
                                      	<tr>
                                            <th align="left">Item</th>
                                            <th align="left">Quantity</th>
                                            <th align="left">Price</th>
                                        </tr>
                                        
                                        <tr><td colspan="3"></td></tr>
                                        <?php $query2 = $db->query("SELECT * FROM order_items WHERE order_id={$query['id']} ORDER BY id"); ?>
                                        <?php $total_price = 0; ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                        <?php while($r2=$db->fetch_array($query2)) { ?>

                                        <?php 

                													if($r2['menu_item_size'] > 0) { 
                														$itemNumber = getData('menu_items','item_number',$r2['menu_item_id']);

                														$itemName 	= getData('menu_items','name',$r2['menu_item_id']);                                    
                                            $itemName_cn   = getData('menu_items','name_cn',$r2['menu_item_id']);

                														$itemValue 	= getData('menu_item_sizes','value',$r2['menu_item_size']);
                                            $itemValue_cn  = getData('menu_item_sizes','value_cn',$r2['menu_item_size']);
                														
                                            $itemPrice 	= getData('menu_item_sizes','price',$r2['menu_item_size']);                														
                                            $itemQuantity = $r2['quantity'];

                													} else {

                														$itemNumber = getData('menu_items','item_number',$r2['menu_item_id']);

                														$itemName 	= getData('menu_items','name',$r2['menu_item_id']);
                                            $itemName_cn   = getData('menu_items','name_cn',$r2['menu_item_id']);
                														
                                            $itemValue	= NULL;
                														$itemPrice 	= getData('menu_items','price',$r2['menu_item_id']);
                														$itemQuantity = $r2['quantity'];
                													}

              												  ?>
                                        <tr>
                                          
                                          <?php
                                          if($r2['menu_item_size'] > 0) { ?>
                                            <td><?php echo "No. ".$itemNumber." ".$itemName." (".$itemName_cn.") ".$itemValue." (".$itemValue_cn.")"; ?></td>
                                          <?php } else { ?>
                                            <td><?php echo "No. ".$itemNumber." ".$itemName." (".$itemName_cn.")"; ?></td>
                                          <?php } ?>

                                            <td><?php echo $itemQuantity; ?>x </td>
                                            <td><?php echo ($itemPrice*$itemQuantity); ?> <?php echo _priceSymbol; ?></td>
                                        </tr>
		<!-- ------------------------------layers code start ------------------------------------------------------------------------------	-->
										<?php $layers_ofitems = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$r2['id']} GROUP BY layer_id "); 
										
										if ($db->affected_rows > 0) { ?>
										
										<?php while($lay_ersofitem=$db->fetch_array($layers_ofitems)) { 
										
										//$layersidd=lay_ersofitem['id'];    // we will use this id in attributes query     ?>
												
										<?php	$layer_row = $db->query_first("SELECT * FROM menu_item_layers WHERE id={$lay_ersofitem['layer_id']}");   ?>
											
											<?php 
											$layername = $layer_row['name'];
											
											?>
											 
												<tr><td colspan="6">
											<div class="main_it_cla" style="">
											<p style=""><?php echo $layername; ?>  </p>
										
											<!-- attribute code start ------------------------------------------------------------------------------	-->
											<?php $attrib = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$r2['id']} AND layer_id={$lay_ersofitem['layer_id']}"); 
												  if ($db->affected_rows > 0) { ?>
											<?php while($attr_val=$db->fetch_array($attrib)) { //echo"<pre>"; print_r(($attr_val);die;?>
										
											<?php $attr_row= $db->query_first("SELECT * FROM layer_lists WHERE id={$attr_val['attribute_id']}"); ?>
											<?php 
											$attribname=$attr_row['name'];
											$attribprice=$attr_row['price'];
											$total_price=$total_price+($attribprice* $attr_val['quantity']);
											?>
											
										<div>
											<div class="item_f" style=""><?php echo $attr_val['quantity']."x ";?><?php echo $attribname; ?></div>
											<div class="item_s" style=""><?php echo _priceSymbol; ?> </div>
											<div class="item_s" style=""><?php echo $attr_val['quantity']*$attribprice;?></div>
											</div>
								
								
								
										<!--	<div>
											<div class="item_f" style=""><?php echo $lay_ersofitem['quantity']."x";?><?php echo $attribname;?></div>
											<div class="item_s" style="">RMB </div>
											<div class="item_s" style=""><?php echo $attribprice*$lay_ersofitem['quantity'];?></div>
											</div> -->
													<?php    
												} // while end of order_item_layers
														}              ?>
                                 
                                  
                                </div>
                                </td></tr>
										
										
									<?php    
												} // while end of order_item_layers
														}              ?>
                               
<!-- ------------------------------------------------- end layers and attributes --------------------------------------------------------- -->
										
										
										
                                                <?php $total_price += ($itemPrice*$itemQuantity); ?>
		                                    <?php } // endwhile $query loop ?>
        	                            <?php } // $db->affected_rows ?>
                                        	<tr><td colspan="3"></td></tr>
                                            <tr>
                                                <th></th>
                                                <th align="left">Total Price</th>
                                                <th align="left"><?php echo $total_price; ?> <?php echo _priceSymbol; ?></th>
                                            </tr>
                                      </table>
                                      
                                      <h3>Additional Notes</h3>
                                      <div class="control-group">
                                        <label class="control-label" for="notes"></label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="notes" name="notes" rows="3" readonly><?php echo $query['notes']; ?></textarea>
                                        </div>
                                      </div>
                                      
                                      <h3>User Information</h3>
                                      
                                      <?php $userInfo = $db->query_first("SELECT * FROM users WHERE id={$query['user_id']}"); ?>
                                      
                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Area</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('areas','title',$userInfo['area_id']); ?></span>
                                        </div>
                                      </div>

                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Building</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('buildings','title',$userInfo['building_id']); ?></span>
                                        </div>
                                      </div>

                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Area (CN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('areas','title_cn',$userInfo['area_id']); ?></span>
                                        </div>
                                      </div>

                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Building (CN)</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo getData('buildings','title_cn',$userInfo['building_id']); ?></span>
                                        </div>
                                      </div>



                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Block / Apt #</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo $userInfo['apartment']; ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Phone</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo $userInfo['phone']; ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Name</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo $userInfo['name']; ?></span>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="user_id">Email</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo $userInfo['email']; ?></span>
                                        </div>
                                      </div>
                                      
                                      <h1>&nbsp;</h1>
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
                                        <label class="control-label">Paid</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="paid" id="paid1" value="1" <?php echo $query['paid']==1 ? 'checked':NULL; ?>>
                                            Yes
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="paid" id="paid2" value="0" <?php echo $query['paid']==0 ? 'checked':NULL; ?>>
                                            No
                                          </label>
                                        </div>
                                      </div>
                                      <!--
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
                                      -->
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
