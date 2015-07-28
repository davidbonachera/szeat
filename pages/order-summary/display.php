<?php $xml = simplexml_load_file("pages/order-summary/content.xml");    // if(isset($_POST)){   echo "<pre>"; print_r($_POST); die;}?>
<style>
.main_it_cla{width:100%; float:left; padding:0 15px; font-size:12px; margin-bottom:15px;}
.main_it_cla p{border-bottom:1px solid hsl(0, 0%, 40%); margin:7px 0; font-weight:bold;}
.item_f{width:41%; float:left;}
.item_s{width:25%; float:left;}
</style>
<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="account-details.php?tab=my-recent-orders"><?php echo ($xml->$lang->myorders==""?$xml->en->myorders:$xml->$lang->myorders); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php echo ($xml->$lang->order==""?$xml->en->order:$xml->$lang->order); ?> #<?php echo $orderID; ?></a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<div class="col-md-8 well">
                <div class="order-summery table-sorter">
                	<div class="myfonts"><h2><?php echo ($xml->$lang->ordersum==""?$xml->en->ordersum:$xml->$lang->ordersum); ?></h2></div>
                    <?php $orderQ = $db->query("SELECT * FROM orders WHERE user_id='{$_SESSION['user']['id']}' AND id='$orderID' ORDER BY date DESC, id DESC LIMIT 5"); ?>
                    <?php if ($db->affected_rows > 0) { ?>
                    <table width="368">
                        <thead>
                            <tr>
                                <th align="left"width="25%"><?php echo ($xml->$lang->date==""?$xml->en->date:$xml->$lang->date); ?> </th>
                                <th align="left"width="25%"><?php echo ($xml->$lang->ordanum==""?$xml->en->ordanum:$xml->$lang->ordanum); ?></th>
                                <th align="left"width="25%"><?php echo ($xml->$lang->resto==""?$xml->en->resto:$xml->$lang->resto); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php while($order=$db->fetch_array($orderQ)) { ?>
                                <tr>
                                    <td><?php echo date($datecountry,strtotime($order['date'])); ?></td>
                                    <td class="red"><a href="order-summary.php?order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                    <td nowrap><?php echo getData("restaurants","name",$order['restaurant_id']); ?></td>
                                </tr>
                            <?php } // end while $order loop ?>
                        </tbody>
                    </table>
                    <?php } // if $db->affected_rows ?>
                    <?php if (isset($_GET['order'])) { ?>
                        <a href="order-summary.php?order=<?php echo $orderID; ?>&reorder=true" class="btn btn-yellow reorder"><?php echo ($xml->$lang->reorder==""?$xml->en->reorder:$xml->$lang->reorder); ?></a>
                    <?php } ?>

                    <a href="#" class="btn btn-yellow print" onclick="window.print(); return false;"><?php echo ($xml->$lang->print==""?$xml->en->print:$xml->$lang->print); ?></a>

                </div>
                <div class="order-summery order-informaiton">
                	<div class="myfonts"><h2><?php echo ($xml->$lang->dets==""?$xml->en->dets:$xml->$lang->dets); ?></h2></div>
                	


                	<?php 

                	if (isset($_SESSION['user']['delivery_fee'])) {
                		echo '<p>Delivery fee: RMB '.$_SESSION['user']['delivery_fee'].'</p>';
                	}
                	?>

                    <table width="100%">
                       <tbody>
                            <?php $items = $db->query("SELECT * FROM order_items WHERE order_id={$orderID}"); ?>
                            <?php $total_price = 0; 
                            if (isset($_SESSION['user']['delivery_fee'])) {
                				$total_price += $_SESSION['user']['delivery_fee'];
                			}
                            if ($db->affected_rows > 0) { ?>
                            	<?php while($item=$db->fetch_array($items)) { ?>
                                	<?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['menu_item_id']}"); ?>
									<?php if ($db->affected_rows > 0) { ?>
                                    	<?php 
											if ($item['menu_item_size'] > 0) { 
												$itemSize  = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['menu_item_size']}");
												$itemValue = $itemSize['value'];
												$itemPrice = number_format($itemSize['price']*$item['quantity'],2);
											} else {
												$itemValue = $ir['value'];
												$itemPrice = number_format($ir['price']*$item['quantity'],2);
											}
										?>
                                        <tr><b>
                                            <td style="float: left; width: 76%;">
											<?php echo $item['quantity']; ?> x  no. <?php __($ir['item_number']); ?> <?php __($ir['name']); ?> <?php echo $itemValue; ?></td>
                                            <td><?php echo _priceSymbol; ?> <?php echo $itemPrice; ?></td> </b>
                                        </tr>
		<!-- ------------------------------layers code start ------------------------------------------------------------------------------	-->
										<?php $layers_ofitems = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$item['id']} GROUP BY layer_id "); 
										
										if ($db->affected_rows > 0) { ?>
										
										<?php while($lay_ersofitem=$db->fetch_array($layers_ofitems)) { 
										
										//$layersidd=lay_ersofitem['id'];    // we will use this id in attributes query     ?>
												
										<?php	$layer_row = $db->query_first("SELECT * FROM menu_item_layers WHERE id={$lay_ersofitem['layer_id']}");   ?>
											
											<?php 
											$layername = $layer_row['name'];
											
											?>
											 
												 <tr>
											<td colspan="4">
											<div class="main_it_cla" style="">
											<p style=""><?php echo $layername; ?>  </p>
										
											<!-- attribute code start ------------------------------------------------------------------------------	-->
											<?php $attrib = $db->query("SELECT * FROM order_item_layers WHERE order_item_id={$item['id']} AND layer_id={$lay_ersofitem['layer_id']}"); 
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
										
                                        <?php $total_price += $itemPrice; ?>
                                    <?php } // $db->affected_rows > 0 ?>
                                <?php } // while $items ?>
                            <?php } // $db->affected_rows ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="15%"><?php echo ($xml->$lang->total==""?$xml->en->total:$xml->$lang->total); ?>:</th>
                                <th width="15%"><?php echo _priceSymbol; ?>  <?php echo number_format($total_price,2); ?></th>
                            </tr>
                       </tfoot>
                    </table>
                </div>
                <div class="order-summery">
                	<div class="myfonts"><h2><?php echo ($xml->$lang->info==""?$xml->en->info:$xml->$lang->info); ?></h2></div>
                    <?php $user = $db->query_first("SELECT * FROM users WHERE id={$_SESSION['user']['id']}"); ?>
                   	<p><?php __($user['name']); ?></p>
                    
                    <p> Tel: <?php __($user['phone']); ?></p>
                    <p> <?php echo getData("areas","title",$user['area_id']); ?></p>


                    <p> <?php echo getData("buildings","title",$user['building_id']); ?></p>
                    <p> <?php __($user['apartment']); ?></p>

                </div>
            </div>
        </div>
    	<!-- end of main content -->
        
    </div>

    <div class="1pushdown"></div>