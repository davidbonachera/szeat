<div class="container main">
    	<div class="row">
        	<div class="col-sm-12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Customer Dashboard</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row myfonts">
        	<div class="col-sm-12">
            	
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#my-acountd-details">My Account Details</a></li>
                    <li><a href="#my-recent-orders">My Recent Orders </a></li>
                    <li><a href="#my-ratings">My Ratings</a></li>
                </ul>

                <?php $uData = $db->query_first("SELECT * FROM users WHERE id={$_SESSION['user']['id']}"); ?>
              	<div class="tab-content">
                    <div class="tab-pane active" id="my-acountd-details">
                    	<div class="row">
                            <form method="post" action="" class="form form-horizontal">
                                <div class="col-sm-6">

                                    <h2 class="page-header">Personal Info</h2>


                                    <div class="form-group form-horizontal">
                                        <label class="control-label col-sm-4">Name:</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="name" value="<?php echo $uData['name']; ?>"  />
                                        </div>
                                    </div>

                                    <div class="form-group form-horizontal">
                                        <label class="control-label col-sm-4">Phone:</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="phone" value="<?php echo $uData['phone']; ?>"  />
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:1em;">
                                        <div class="col-md-offset-4 col-md-1">
                                            <button id="change_password" type="button" class="btn btn-info">Change Password</button>
                                        </div>
                                    </div>                                    
                                        
                                        <div class="clearfix">&nbsp;</div>
                                        <?php if (isset($_SESSION['msg'])) { ?>
                                            <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                                                <br /><?php echo $_SESSION['msg']; ?>
                                            </div>
                                        <?php } // isset $_SESSION['msg'] ?>
                                    </div>
                                    <div class="col-sm-5">

                                        <h2 class="page-header">Delivery Details</h2>

                                        <div class="form-group form-horizontal">
                                            <label class="control-label col-sm-4">Your Area:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" id="area" name="area">
                                                    <option hidden="">Select</option>
                                                    <?php $areas = $db->query("SELECT * FROM areas WHERE $status=1 ORDER BY title ASC"); ?>
                                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                                    <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['area_id'] ? 'selected':NULL; ?>>
                                                        <?php echo ($lang=='cn'?($r['title_cn']==""?$r['title']:$r['title_cn']):$r['title']); ?>
                                                    </option>
                                                 <?php } // while $areas loop ?>
                                                </select>
                                            </div>
                                         </div>

                                        <div class="form-group form-horizontal">
                                            <label class="control-label col-sm-4">Your Building:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" id="building" name="building">
                                                    <option hidden="hidden">Select</option>
                                                    <?php $cuisines = $db->query("SELECT * FROM buildings WHERE $status=1 ORDER BY title ASC"); ?>
                                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                                        <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['building_id'] ? 'selected':NULL; ?>>
                                                            <?php echo ($lang=='cn'?($r['title_cn']==""?$r['title']:$r['title_cn']):$r['title']); ?>
                                                        </option>
                                                    <?php } // while $areas loop ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group form-horizontal">
                                            <label class="control-label col-sm-4">Block / Apt:</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="apartment" placeholder="Building 6, Apt. 10 E" value="<?php echo $uData['apartment']; ?>"/>
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-top:1em;">
                                            <div class="col-md-offset-1 col-md-1">
                                                <button type="submit" class="btn btn-yellow">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="my-recent-orders">
                            <h2 class="page-header">Order Overview</h2>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th align="left">Date </th>
                                        <th align="left">Order Number</th>
                                        <th align="left">Restaurant</th>
                                        <th align="left">Price</th>
                                        <th align="left">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
										if (isset($_GET['year'])) {
											$year 	= $db->escape($_GET['year']);
											$oq 	= $db->query("SELECT * FROM orders WHERE user_id={$_SESSION['user']['id']} AND status=1 AND YEAR(date)='$year' ORDER BY date DESC"); 
										}else{ 
											$oq = $db->query("SELECT * FROM orders WHERE user_id={$_SESSION['user']['id']} AND status=1 ORDER BY date DESC");
										}
									?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($order=$db->fetch_array($oq)) { ?>
                                            <tr>
                                                <td><?php echo date("m/d/Y",strtotime($order['date'])); ?></td>
                                                <td><a href="page=order-summary&order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                                <td><?php echo getData('restaurants','name',$order['restaurant_id']); ?> &nbsp; </td>
                                                <td><?php echo $order['price']; ?> <?php echo _priceSymbol; ?></td>
                                                <td><?php 
														$rating = $db->query_first("SELECT * FROM ratings WHERE order_id={$order['id']}");
														if ($db->affected_rows > 0) { 
															if ($rating['status']==1) {
																echo '<span class="rating">';
																	for($i=$rating['ratings']; $i<=4; $i++) 
																		echo '<span class="star"></span>';
																	for($i=1; $i<=$rating['ratings']; $i++) 
																		echo '<span class="star rated"></span>';
																echo '</span>';
															} else {
																echo '<a href="index.php?page=rate-takeaway&order='.$order['id'].'">Pending Approval</a>';
															}
														} else {
													?>
                                                    	<a href="index.php?page=rate-takeaway&order=<?php echo $order['id']; ?>">Rate Now</a>
                                                    <?php } ?>
												</td>
                                            </tr>
                                        <?php } // while $order loop ?>
                                    <?php } // $db->affected_rows ?>
                                </tbody>
                            </table>
                            <?php 
								$oq = $db->query("SELECT * FROM orders WHERE user_id={$_SESSION['user']['id']} AND status=1"); 
								if ($db->affected_rows > 0) {
									while($r=$db->fetch_array($oq)) {
										$year = date("Y",strtotime($r['date']));
										$yearsArray[$year][] = $r['id'];
									}
								}
							?>
                            <?php if (sizeof($yearsArray) > 0) { ?>
                                <div class="namber-rate">
                                    <h4>Number of orders in <?php echo date("Y").': '.count($yearsArray[date("Y")]); ?></h4>
                                    <p><?php foreach ($yearsArray as $key=>$val) echo '<a href="?year='.$key.'&tab=my-recent-orders">'.$key.'</a> | '; ?></p>
                                </div>
                            <?php } // sizeof ?>
                        </div>
                        <div class="tab-pane" id="my-ratings">
                        	<h2 class="page-header">Ratings</h2>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th align="left">Date </th>
                                        <th align="left">Order number</th>
                                        <th align="left">Restaurant</th>
                                        <th align="left">Rating</th>
                                        <th align="left">Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
										if (isset($_GET['year'])) {
											$year 	= $db->escape($_GET['year']);
											$rq 	= $db->query("SELECT * FROM ratings WHERE user_id={$_SESSION['user']['id']} AND status=1 AND YEAR(date)='$year' ORDER BY date DESC"); 
										}else{ 
											$rq = $db->query("SELECT * FROM ratings WHERE user_id={$_SESSION['user']['id']} AND status=1 ORDER BY date DESC"); 
										}
									?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($rating=$db->fetch_array($rq)) { ?>
                                            <tr>
                                                <td><?php echo date("m/d/Y",strtotime($rating['date'])); ?></td>
                                                <td><a href="order-summary.php?order=<?php echo $rating['order_id']; ?>"><?php echo $rating['order_id']; ?></a></td>
                                                <td><?php echo getData('restaurants','name',$rating['restaurant_id']); ?> &nbsp; </td>
                                                <td><span class="rating">
														<?php 
                                                            for($i=$rating['ratings']; $i<=4; $i++) { 
                                                                echo '<span class="star"></span>';
                                                            } // endfor;
                                                            for($i=1; $i<=$rating['ratings']; $i++) { 
                                                                echo '<span class="star rated"></span>';
                                                            } // endfor; 
                                                        ?>
                                                    </span>
                                            	</td>
                                                <td><?php echo $rating['comments']; ?></td>
                                            </tr>
                                    	<?php } // while $order loop ?>
                                    <?php } // $db->affected_rows ?>
                                </tbody>
                            </table>
                            <?php 
								$yearsArray = array();
								$rq = $db->query("SELECT * FROM ratings WHERE user_id={$_SESSION['user']['id']} AND status=1"); 
								if ($db->affected_rows > 0) {
									while($r=$db->fetch_array($rq)) {
										$year = date("Y",strtotime($r['date']));
										$yearsArray[$year][] = $r['id'];
									}
								}
							?>
                            <div class="clearfix"></div>
                            <?php if (sizeof($yearsArray) > 0) { ?>
                                <div class="namber-rate">
                                    <h4>Number of ratings in <?php echo date("Y").': '.count($yearsArray[date("Y")]); ?></h4>
                                    <p><?php foreach ($yearsArray as $key=>$val) echo '<a href="?year='.$key.'&tab=my-ratings">'.$key.'</a> | '; ?></p>
                                </div>
                            <?php } // sizeof ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    	<!-- end of main content -->