<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php isLoggedIn(); ?>
<?php
if ($_POST) {
	if (checkFeild($_POST['name'])) {
		if (checkFeild($_POST['phone'])) {
			if (checkFeild($_POST['area'])) {
				if (checkFeild($_POST['building'])) {
					
					$data['name'] 			= $db->escape($_POST['name']);
					$data['phone'] 			= $db->escape($_POST['phone']);
					$data['area_id'] 		= $db->escape($_POST['area']);
					$data['building_id'] 	= $db->escape($_POST['building']);
					$data['apartment'] 		= $db->escape($_POST['apartment']);
					
					if ($db->query_update("users",$data,"id={$_SESSION['user']['id']}")) {
						$_SESSION['error'] = false;
						$_SESSION['msg']   = 'Your details have been saved successfully.';
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
					}
					
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Please select your building.';
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Please select your area.';
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your phone number.';
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg']   = 'Please enter your phone name.';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Account Details - <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
    <script type="text/javascript" charset="utf-8">
	$(function(){
	  $("select#area").change(function(){
		$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		  $("select#building").html(options);
		})
	  })
	})
	</script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Customer Dashboard</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<div class="span12">
            	<div class="block-page acountd-details">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#my-acountd-details">My Account Details</a></li>
                        <li><a href="#my-recent-orders">My Recent Orders </a></li>
                        <li><a href="#my-ratings">My Ratings</a></li>
                    </ul>
                    <?php $uData = $db->query_first("SELECT * FROM users WHERE id={$_SESSION['user']['id']}"); ?>
                  	<div class="tab-content">
                        <div class="tab-pane active" id="my-acountd-details">
                        	<div class="row">
                                <form method="post" action="">
                                    <div class="span6 detalis-forms">
                                        <h3>Your Details:</h3>
                                        <div>
                                            <label>Name:</label>
                                            <input type="text" name="name" value="<?php echo $uData['name']; ?>"  />
                                        </div>
                                        <div>
                                            <label>Phone number:</label>
                                            <input type="text" name="phone" value="<?php echo $uData['phone']; ?>" />
                                        </div>
                                        <button id="change_password" type="button" class="gray-button">Change Password</button>
                                        
                                        <div class="clearfix">&nbsp;</div>
                                        <?php if (isset($_SESSION['msg'])) { ?>
                                            <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>">
                                                <br /><?php echo $_SESSION['msg']; ?>
                                            </div>
                                        <?php } // isset $_SESSION['msg'] ?>
                                    </div>
                                    <div class="span5 detalis-forms">
                                        <h3>Delivery Address:</h3>
                                        <div>
                                            <label> Your Area:</label>
                                            <select name="area" id="area">
                                                <option hidden="">Select</option>
                                                <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                                <?php while ($r=$db->fetch_array($areas)) { ?>
                                                    <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['area_id'] ? 'selected':NULL; ?>>
                                                        <?php __($r['title']); ?>
                                                    </option>
                                                <?php } // while $areas loop ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label>Your Building:</label>
                                            <select name="building" id="building">
                                                <option hidden="hidden">Select</option>
                                                <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                                <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                                    <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['building_id'] ? 'selected':NULL; ?>>
                                                        <?php __($r['title']); ?>
                                                    </option>
                                                <?php } // while $areas loop ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label>Your Block / Apt #:</label>
                                            <input type="text" name="apartment" placeholder="Building 6, Apt. 10 E" value="<?php echo $uData['apartment']; ?>"/>
                                        </div>
                                        <button type="submit" class="yellow-button">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="my-recent-orders">
                            <h3 class="page-header">Order Overview</h3>
                            <table width="1030">
                                <thead>
                                    <tr>
                                        <th width="15%" align="left">Date </th>
                                        <th width="15%" align="left">Order Number</th>
                                        <th width="15%" align="left">Restaurant</th>
                                        <th width="15%" align="left">Price</th>
                                        <th width="40%" align="left">Rating</th>
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
                                                <td class="red"><a href="order-summary.php?order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                                <td nowrap><?php echo getData('restaurants','name',$order['restaurant_id']); ?> &nbsp; </td>
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
																echo '<a href="rate-takeaway.php?order='.$order['id'].'">Pending Approval</a>';
															}
														} else {
													?>
                                                    	<a href="rate-takeaway.php?order=<?php echo $order['id']; ?>">Rate Now</a>
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
                        	<h3 class="page-header">Ratings</h3>
                            <table width="1030" style="min-height:70px;">
                                <thead>
                                    <tr>
                                        <th width="15%" align="left">Date </th>
                                        <th width="15%" align="left">Order number</th>
                                        <th width="15%" align="left">Restaurant</th>
                                        <th width="15%" align="left">Rating</th>
                                        <th width="40%" align="left">Review</th>
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
                                                <td class="red"><a href="order-summary.php?order=<?php echo $rating['order_id']; ?>"><?php echo $rating['order_id']; ?></a></td>
                                                <td nowrap><?php echo getData('restaurants','name',$rating['restaurant_id']); ?> &nbsp; </td>
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
    </div>
    <?php require_once('includes/footer.php'); ?>
    <script type="text/javascript">
		$('#myTab a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		})
		$(document).ready(function(){
			var param = getURLParameter('tab');
			if (param!="") {
				$('#myTab a[href=#'+param+']').tab('show') ;
			}
		});
		$('#change_password').click(function() {
			window.location = "change-password.php";
		});
		function getURLParameter(name) {
			return decodeURI(
				(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
			);
		}
		

	</script> 
</body>
</html>
