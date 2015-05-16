<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="account-details.php?tab=my-recent-orders">My Orders</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Rate your takeaway experience</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<!-- <div class="span12"> -->
            	<div class="block-page your-takeaway">
                    <h3 class="page-header">Rate your takeaway experience</h3>
                    <table width="560">
                        <thead>
                            <tr>
                                <th align="left"width="25%">Date </th>
                                <th align="left"width="25%">Order number</th>
                                <th align="left"width="25%">Restaurant</th>
                                <th align="left"width="25%">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo date("m/d/Y",strtotime($order['date'])); ?></td>
                                <td class="red"><a href="order-summary.php?order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                <td nowrap><?php echo getData('restaurants','name',$order['restaurant_id']); ?> &nbsp; </td>
                                <td><?php echo $order['price']; ?> <?php echo _priceSymbol; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                    	Did you enjoy the service and food 
                        you received from the restaurant? 
                        Let other Shenzen Eat'ers know!
                    </p>
                    <p>
                    	Use the scale below, then give 
                        us the lowdown – the restaurants 
                        appreciate the feedback too, so the 
                        more details you give, the more they 
                        can improve their service and other 
                        customers can make informed decisions
                    </p>
                    <p>
                    	Note:  It can take up to 48 hours for reviews to be approved, reviews that contain offensive, abusive or racist language will not be tolerated (Use the 'Nan Rule': If you'd be embarrassed to say it while your nan was in the room, don't say it here!).
                    </p>
                    
                    <div class="clearfix">&nbsp;</div>
					<?php if (isset($_SESSION['msg'])) { ?>
                        <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                            <br /><?php echo $_SESSION['msg']; ?>
                        </div>
                    <?php } // isset $_SESSION['msg'] ?>
                    
                    <?php $cr = $db->query_first("SELECT * FROM ratings WHERE user_id={$_SESSION['user']['id']} AND order_id=$orderID"); ?>
                    <?php if ($db->affected_rows==0) { ?>
                        <div class="rate-your-star">
                            <div class="rate-take-container">
                                <strong>Star Rating</strong>
                                <span class="rating" id="stars">
                                    <span class="star" id="star5"></span><span class="star" id="star4"></span><span class="star" id="star3"></span><span class="star" id="star2"></span><span class="star" id="star1"></span>
                                </span>
                            </div>
                            <form action="" method="post">
                                <label>Comments (optional):</label>
                                <textarea name="comments" id="comments"></textarea>
                                <input type="hidden" name="rating" id="rating" value="0" />
                                <button type="submit" class="yellow-button">Save</button>
                            </form>
                        </div>
                    <?php } else { // $db->affected_rows ?>
                    	<div class="rate-your-star">
                            <div class="rate-take-container">
                                <strong>Your Rating</strong>
                                <span class="rating">
									<?php 
                                        for($i=$cr['ratings']; $i<=4; $i++) { 
                                            echo '<span class="star"></span>';
                                        } // endfor;
                                        for($i=1; $i<=$cr['ratings']; $i++) { 
                                            echo '<span class="star rated"></span>';
                                        } // endfor; 
                                    ?>
                                </span>
                            </div>
                            <form action="" method="post">
                                <label>Comments:</label>
                                <p><?php echo $cr['comments']; ?></p>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            <!-- </div> -->
        </div>
    	<!-- end of main content -->
    </div>