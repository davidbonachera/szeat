<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php isLoggedIn(); ?>
<?php
if (isset($_GET['order'])) {
	$orderID = $db->escape($_GET['order']);
	$order = $db->query_first("SELECT * FROM orders WHERE id=$orderID AND user_id={$_SESSION['user']['id']} AND status=1");
	if ($db->affected_rows < 1) {
		header("Location: account-details.php?order-unavailable");
		exit;
	}
} else {
	header("Location: account-details.php?order-not-found");
	exit;
}

if ($_POST) {
	if (checkFeild($_POST['rating'])) {
		
		$data['user_id'] 		= $_SESSION['user']['id'];
		$data['order_id'] 		= $orderID;
		$data['restaurant_id'] 	= $order['restaurant_id'];
		$data['ratings'] 		= $db->escape($_POST['rating']);
		$data['comments'] 		= $db->escape(strip_tags($_POST['comments'],NULL));
		$data['status']			= 0;
		
		if ($db->query_insert("ratings",$data)) {
			$_SESSION['error'] = false;
			$_SESSION['msg']   = 'Your ratings have been saved successfully.';
			header("Location: rate-takeaway.php?order=$orderID");
			exit;
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
		}
		
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg']   = 'Please select star rating.';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rate Your Takeaway - <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
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
        	<div class="span12">
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
            </div>
        </div>
    	<!-- end of main content -->
    </div>
    <?php require_once('includes/footer.php'); ?>
    <script type="text/javascript">
		$('#star1').click(function() {
            $('#rating').val(1);
			$("#stars span").removeClass("starred");
			$("#stars #star1").addClass("starred");
            return false;
        });
		$('#star2').click(function() {
            $('#rating').val(2);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2").addClass("starred");
            return false;
        });
		$('#star3').click(function() {
            $('#rating').val(3);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3").addClass("starred");
            return false;
        });
		$('#star4').click(function() {
            $('#rating').val(4);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3, #stars #star4").addClass("starred");
            return false;
        });
		$('#star5').click(function() {
            $('#rating').val(5);
			$("#stars span").removeClass("starred");
			$("#stars #star1, #stars #star2, #stars #star3, #stars #star4, #stars #star5").addClass("starred");
            return false;
        });
	</script> 
</body>
</html>
