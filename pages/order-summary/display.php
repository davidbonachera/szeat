<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="account-details.php?tab=my-recent-orders">My Orders</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Order #<?php echo $orderID; ?></a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<div class="col-md-8 well">
                <div class="order-summery table-sorter">
                	<div class="myfonts"><h2>Order summary</h2></div>
                    <?php $orderQ = $db->query("SELECT * FROM orders WHERE user_id='{$_SESSION['user']['id']}' AND id='$orderID' ORDER BY date DESC, id DESC LIMIT 5"); ?>
                    <?php if ($db->affected_rows > 0) { ?>
                    <table width="368">
                        <thead>
                            <tr>
                                <th align="left"width="25%">Date </th>
                                <th align="left"width="25%">Order number</th>
                                <th align="left"width="25%">Restaurant</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php while($order=$db->fetch_array($orderQ)) { ?>
                                <tr>
                                    <td><?php echo date("m/d/Y",strtotime($order['date'])); ?></td>
                                    <td class="red"><a href="order-summary.php?order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                    <td nowrap><?php echo getData("restaurants","name",$order['restaurant_id']); ?></td>
                                </tr>
                            <?php } // end while $order loop ?>
                        </tbody>
                    </table>
                    <?php } // if $db->affected_rows ?>
                    <?php if (isset($_GET['order'])) { ?>
                        <a href="order-summary.php?order=<?php echo $orderID; ?>&reorder=true" class="yellow-button reorder">Reorder</a>
                    <?php } ?>

                    <a href="#" class="btn btn-yellow print" onclick="window.print(); return false;">Print</a>

                </div>
                <div class="order-summery order-informaiton">
                	<div class="myfonts"><h2>Order details</h2></div>
                	


                	<?php 

                	if (isset($_SESSION['user']['delivery_fee'])) {
                		echo '<p>Delivery fee: RMB '.$_SESSION['user']['delivery_fee'].'</p>';
                	}
                	?>

                    <table width="368">
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
                                        <tr>
                                            <td nowrap><?php echo $item['quantity']; ?> x  no. <?php __($ir['item_number']); ?> <?php __($ir['name']); ?> <?php echo $itemValue; ?></td>
                                            <td><?php echo _priceSymbol; ?> <?php echo $itemPrice; ?></td>
                                        </tr>
                                        <?php $total_price += $itemPrice; ?>
                                    <?php } // $db->affected_rows > 0 ?>
                                <?php } // while $items ?>
                            <?php } // $db->affected_rows ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th width="15%">Total:</th>
                                <th width="15%"><?php echo _priceSymbol; ?>  <?php echo $total_price; ?></th>
                            </tr>
                       </tfoot>
                    </table>
                </div>
                <div class="order-summery">
                	<div class="myfonts"><h2>Delivery information</h2></div>
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