<?php 
require_once("../class/config.inc.php");
require_once("../class/class.phpmailer.php");
require_once("../class/FileUpload.class.php");
require_once("../class/Pagination.class.php");
include("include/functions.php");

is_session();

if (checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);
	$res = $db->query_first("SELECT r.*, a.title AS area, b.title AS building FROM restaurants r
							 LEFT JOIN restaurants_areas ra ON r.id=ra.restaurant_id
							 LEFT JOIN restaurants_buildings rb ON r.id=rb.restaurant_id
							 LEFT JOIN areas a ON ra.area_id=a.id
							 LEFT JOIN buildings b ON rb.building_id=b.id
							 WHERE r.id=$id");
	$total_rows = $db->affected_rows;
	
} else {
	header("Location: invoicing.php");
	exit;
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Invoice</title>
    <style type="text/css">
	BODY {
		font-family:"Century Gothic", "Times New Roman", Calibri, Verdana, Arial, Sans-serif;
	}
	H1, H2, H3, H4, H5 {
		margin-bottom:0;
	}
	TABLE.invoices {
		font-size:14px;
		border:1px solid #111;
	}
	TABLE THEAD TR TH {
		padding:5px;
		text-align:left;
	}
	TABLE THEAD TR TD {
		padding:2px;
	}
	.spacer_short TD {
		height:50px;
	}
	.spacer_tall TD {
		height:100px;
	}
	.address, .date, .totals TD {
		font-size:16px;
	}
	</style>
	
	
	
	
</head>


<body>
<table align="center" border="0">
	<tr>
		<td>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td align="left" width="400">
                        <h3><?php echo $res['name']; ?></h3>
                        <h4><?php echo nl2br($res['mailing_address']); ?></h4>
                    </td>
                    <td></td>
                    <td>
                        <img src="../img/logo_new2.png" width="330" style="float:right;" />
                    </td>
                </tr>
                <tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                    <td class="date">Date of Invoice Generation: <?php echo date("d-m-Y"); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            </table>
            <table border="0" cellpadding="5" cellspacing="0" class="invoices">
              <thead>
                  <tr bgcolor="#999999">
                      <th width="80">Date</th>
                      <th width="80">Order #</th>
                      <th width="150">Menu Item</th>
                      <th width="100">Price</th>
                      <th width="100">Customer</th>
                      <th width="100">Comission</th>
                  </tr>
              </thead>   
              <tbody>
                <?php
					if (is_array($_POST['invoice'])) {
						$invoices = implode(",", $_POST['invoice']);
					} else {
						$invoices = 0;
					}
                    $query = $db->query("SELECT * FROM order_items WHERE id IN ($invoices)"); 
                ?>
                <?php if ($db->affected_rows > 0) { ?>
                	<?php 
						$page				= 0;
						$totalPrice 		= 0;
						$totalCommission 	= 0;
						$attr_price = 0;
						$attr_commision =0;
					?>
                    <?php while($r=$db->fetch_array($query)) { ?>
                        <?php isset($count) ? $count++:$count=1; ?>
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
						
						
                        <tr height="auto" bgcolor="<?php echo $count%2==0 ? '#CCCCCC':NULL; ?>">
                            <td width="80"><?php echo date("d-m-y",strtotime($r['date'])); ?></td>
                            <td width="80"><?php echo $r['order_id']; ?></td>
                            <td width="150"><?php echo $itemQuantity." x no. ".$itemNumber." ".$itemName." ".$itemValue; ?></td>
                            <td width="100">
	                            <?php echo _priceSymbol; ?> 
				
							
								<?php
									$sumPrice = number_format($itemPrice*$itemQuantity, 2);
                                    echo $sumPrice;
                                ?>
                            </td>
                            <td width="100"><?php echo getData("users","name",$r['user_id']); ?></td>
                            <td width="100">
							
                            	<?php 
									if ($res['comission_type']=='fixed') {
										$comission = ($res['comission_value']*$itemQuantity);
									} elseif ($res['comission_type']=='percent') {
										$comission = ((($itemPrice*$res['comission_value'])/100)*$itemQuantity);
									}
									echo _priceSymbol.' '.number_format($comission,2);
								?>
                            </td>
                        </tr>
						<!-- ------------------------------layers code start ------------------------------------------------------------------------------	-->
			<?php $only_atbprice=0; ?>
										<?php $layers_ofitems = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$r['id']} GROUP BY layer_id "); 
										
										if ($db->affected_rows > 0) { ?>
										
										<?php while($lay_ersofitem=$db->fetch_array($layers_ofitems)) { 
										
										//$layersidd=lay_ersofitem['id'];    // we will use this id in attributes query     ?>
												
										<?php	$layer_row = $db->query_first("SELECT * FROM menu_item_layers WHERE id={$lay_ersofitem['layer_id']}");   ?>
											
											<?php 
											$layername = $layer_row['name'];
											
											?>
											 
												<tr bgcolor="<?php echo $count%2==0 ? '#CCCCCC':NULL; ?>">
											<td width="80"> <i><?php echo $layername; ?> </i> </td>
											
											  <td width="80"></td>
											  <td width="150"></td>
											  <td width="100"></td>
											  <td width="100"></td>
											  <td width="100"></td>
																	
											
											</tr>
											
										
						<!-- attribute code start ------------------------------------------------------------------------------	-->
											<?php $attrib = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$r['id']} AND layer_id={$lay_ersofitem['layer_id']}"); 
												  if ($db->affected_rows > 0) { ?>
											<?php while($attr_val=$db->fetch_array($attrib)) { //echo"<pre>"; print_r(($attr_val);die;?>
										
											<?php $attr_row= $db->query_first("SELECT * FROM layer_lists WHERE id={$attr_val['attribute_id']}"); ?>
											<?php 
											$attribname=$attr_row['name'];
											$attribprice=$attr_row['price'];
											$sumPrice=$sumPrice+($attribprice* $attr_val['quantity']);
											
											$only_atbprice=$only_atbprice+($attribprice* $attr_val['quantity']);
											?>
											
										
								
									<tr bgcolor="<?php echo $count%2==0 ? '#CCCCCC':NULL; ?>">
											<td width="80">   </td>
											<td width="80">   </td>
											<td width="150"> <?php echo $attr_val['quantity']."x ";?><?php echo $attribname; ?></td>
											 <td width="100"><?php echo _priceSymbol; ?><?php echo $attr_val['quantity']*$attribprice;?></td>
											  <td width="100"></td>
											  <td width="100"></td>
											
								</tr>
								
								
								
								
								
								
								
										<!--	<div>
											<div class="item_f" style=""><?php echo $lay_ersofitem['quantity']."x";?><?php echo $attribname;?></div>
											<div class="item_s" style="">RMB </div>
											<div class="item_s" style=""><?php echo $attribprice*$lay_ersofitem['quantity'];?></div>
											</div> -->
													<?php    
												} // while end of order_item_layers
														}              ?>
                                
                                  
                             
										
										
									<?php    
												} // while end of order_item_layers
														}              ?>
														
							
                            	<?php 
									if ($res['comission_type']=='fixed') {
										$comission2 = ($res['comission_value']*$itemQuantity);
									} elseif ($res['comission_type']=='percent') {
										$comission2 = ((($only_atbprice*$res['comission_value'])/100)*$itemQuantity);
									}
									
									$totalCommission=$comission2+$totalCommission;
									
									
									
									//echo number_format($comission2,2);
								?>
									<tr bgcolor="<?php echo $count%2==0 ? '#CCCCCC':NULL; ?>">
											<td width="80">   </td>
											<td width="80"> </td>
											<td width="150"><b>Total Attributes</b>   </td>
											 <td width="100"><?php echo _priceSymbol; ?><?php echo $only_atbprice; ?></td>
											  <td width="100"></td>
											  <td width="100"><?php echo _priceSymbol; ?> <?php echo number_format($comission2,2); ?></td>
											
							</tr>
								
								
								
								
<!-- ------------------------------------------------- end layers and attributes --------------------------------------------------------- -->
						
						
                        <?php 
							$totalPrice += $sumPrice; 
							//$comission=$comission+$attr_commision;
							$totalCommission += $comission;
						?>
<?php if ($count%5==0) { ?>
	<?php $page++; ?>
              </tbody>
            </table>
		</td>
	</tr>
</table>
<table align="center" border="0">
	<tr>
		<td>
            <table border="0" cellpadding="5" cellspacing="0" class="invoices"> 
              <thead>
                  <tr bgcolor="#FFFFFF" style="color:#FFFFFF;">
                      <th width="80">Date</th>
                      <th width="80">Order #</th>
                      <th width="150">Menu Item</th>
                      <th width="100">Price</th>
                      <th width="100">Customer</th>
                      <th width="100">Comission</th>
                  </tr>
              </thead>
              <tbody>
<?php } ?>
                    <?php } // endwhile $query loop ?>
                <?php } // $db->affected_rows ?>
              </tbody>
            </table>
		</td>
	</tr>
</table>
<table align="center" border="0">
	<tr>
		<td>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr class="totals">
                    <td width="150"></td>
                    <td width="400" align="right">Total Order(s) Value:</td>
                    <td width="150" align="right">
						<?php echo _priceSymbol; ?> 
						<?php echo number_format($totalPrice,2); ?>
                    </td>
                </tr>
                <tr class="totals">
                	<td></td>
                    <td align="right">Total Commission:</td>
                    <td align="right">
                    	<?php echo _priceSymbol; ?> 
						<?php echo number_format($totalCommission,2);d?>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
</table>
<table align="center" border="0">
	<tr>
		<td>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                <?php $cofiguration = $db->query_first("SELECT * FROM config WHERE id=1"); ?>
                <tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                	<td width="150"></td>
                    <td width="400"><?php echo $cofiguration['bank_details']; ?></td>
                    <td width="100"></td>
				</tr>
			</table>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                	<td width="150"></td>
                    <td width="400" align="center"><h3>Thank you for your business!</h3></td>
                    <td width="100"></td>
				</tr>
			</table>
            <table border="0" width="100%" cellpadding="5" cellspacing="0">
            	<tr class="spacer_short"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                	<td width="150">&nbsp;</td>
                    <td width="250">&nbsp;</td>
                    <td width="250" class="address">
                    	<strong><?php echo $cofiguration['title']; ?></strong><br />
                        <?php echo $cofiguration['address']; ?><br />
                        Tel: <?php echo $cofiguration['tele']; ?><br />
                        Email: <?php echo $cofiguration['email']; ?>
                    </td>
                </tr>
            </table>
		</td>
	</tr>
</table>
</body>
</html>
<?php 
	require_once('html2pdf/html2pdf.class.php');
	$date		= date("dmY");
	$content 	= ob_get_clean();
	$html2pdf 	= new HTML2PDF('P','A4','en');
	$html2pdf->WriteHTML($content);
	$html2pdf->Output("invoice-$date.pdf");	
?>