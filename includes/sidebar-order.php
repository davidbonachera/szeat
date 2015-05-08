<div class="span2 my-span-order">
    <?php if (isset($_SESSION['error']) && $_SESSION['error']==true) { ?>
    	<div class="error"><?php echo $_SESSION['msg']; ?></div>
    <?php } ?>
    
    <div class="block-page your-order fixedWidth179" id="yourOrder">
        <h2>Your order</h2>
        <ul>
            <?php if (isset($_SESSION['user']['items'])) { ?>
            	<?php $totalItemPrice = 0; ?>
				<?php foreach ($_SESSION['user']['items'] as $key=>$item) { ?>
                	<?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['id']} AND status=1"); ?>
                    <?php if ($db->affected_rows > 0) { ?>
						<?php 
							if ($item['size'] > 0) { 
								$itemSize  = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['size']}");
								$itemValue = $itemSize['value'];
								$itemPrice = $itemSize['price'];
							} else {
								$itemValue = $ir['value'];
								$itemPrice = $ir['price'];
							}
						?>
                        <li>
                            <p><?php __($ir['name']); ?>  <?php echo $itemValue; ?></p>
                            <span><?php echo $item['quantity']; ?>x</span>
                            <span><?php echo _priceSymbol; ?></span>
                            <?php $itemPriceCalculated = number_format($itemPrice*$item['quantity'],2); ?>
                            <?php $totalItemPrice += $itemPriceCalculated; ?>
                            <i><?php echo $itemPriceCalculated; ?><a href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>&remove_item=<?php echo $item['id']; ?>&size=<?php echo $item['size']; ?>"><img src="img/order-delete.png" alt=""  /></a></i>
                        </li>
                    <?php } // $db->affected_rows > 0 ?>
                <?php } // endforeach 

                            
                        ?>
                <li>
                    <p>Subtotal:</p>
                    <span>&nbsp;</span>
                    <span style="font-weight:normal"><?php echo _priceSymbol; ?></span>
                    <i style="font-weight:normal"><?php echo number_format($totalItemPrice,2); ?></i>
                </li>
			<?php } // end isset ?>
        </ul>

        <?php 
            // $minOrda = $res['minimum_order'];
            // $minSpend = $db->query("SELECT * FROM restaurants WHERE id=$rID AND $totalItemPrice>=$minOrda");    

            // if ($db->affected_rows > 0) {
            //     $spendEnough = true;
            // } else { 
            //     $spendEnough = false;
            //     echo '<p class="redwarning">You have not reached the restaurant\'s minimum order yet.</p>';
            // } 

        $spendEnough = false;
        $minOrda = $res['minimum_order'];

        if ($totalItemPrice>=$minOrda) {
            $spendEnough=true;
        } else {
            $spendEnough=false;
            echo '<p class="redwarning">You have not reached the restaurant\'s minimum order yet.</p>';
        }

        

        ?>

        <div class="order-button">
            <div class="view-menu"><a class="yellow-button" href="<?php echo (($deliveryAvailable==false)||($spendEnough!=true)) ? "":"order-details.php"; ?>">Order Now</a></div>
        </div>
    </div>
</div>