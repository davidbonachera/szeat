<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php
if (isset($_GET['id'])) {
	$rID = $db->escape($_GET['id']);
	$res = $db->query_first("SELECT * FROM restaurants WHERE id=$rID");
	
	if (isset($_SESSION['user']['restaurant']['id']) && $_SESSION['user']['restaurant']['id']!=$res['id']) {
		if (isset($_SESSION['user']['items'])) {
			unset($_SESSION['user']['items']);
		}
	}
	
	$_SESSION['user']['restaurant']['name'] = $res['name'];
	$_SESSION['user']['restaurant']['id'] 	= $res['id'];
	
} elseif (isset($_GET['restaurant'])) {
	$str = str_replace(array('_','+'), array('&',' '), html_entity_decode($_GET['restaurant']));
	$res = $db->query_first("SELECT * FROM restaurants WHERE name LIKE '%$str%' AND status=1 LIMIT 1");
	
	if (isset($_SESSION['user']['restaurant']['id']) && $_SESSION['user']['restaurant']['id']!=$res['id']) {
		if (isset($_SESSION['user']['items'])) {
			unset($_SESSION['user']['items']);
		}
	}
	
	$_SESSION['user']['restaurant']['name'] = $res['name'];
	$_SESSION['user']['restaurant']['id'] 	= $res['id'];
} else {
	header("Location: index.php");
	exit;
}
if ($db->affected_rows > 0) {
	// do nothing
} else {
	header("Location: index.php?404");
	exit;
}
?>


<?php
	$currentDay = strtoupper(date("l"));
	$time = $db->query("SELECT * FROM delivery_times WHERE CURRENT_TIME() BETWEEN TIME(`start`) AND TIME(`end`) AND day='$currentDay' AND restaurant_id={$_SESSION['user']['restaurant']['id']} AND status=1");    
	if ($db->affected_rows > 0) {
		$deliveryAvailable = true;
	} else {
		$deliveryAvailable = false;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo htmlentities($res['name']); ?> <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <link rel="stylesheet" type="text/css" href="css/custom.css"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
    <link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$.fn.prettyPhoto({
				default_width: 400,
				default_height: 130,
				animation_speed: 'fast', 	/* fast/slow/normal */
				show_title: false, 			/* true/false */
				theme: 'facebook', 			/* light_rounded / dark_rounded / light_square / dark_square / facebook */
				modal: false, 				/* If set to true, only the close button will close the window */
				overlay_gallery: false, 	/* If set to true, a gallery will overlay the fullscreen image on mouse over */
				keyboard_shortcuts: false, 	/* Set to false if you open forms inside prettyPhoto */
				social_tools: '' 			/* html or false to disable */
			});
			<?php if ($deliveryAvailable==false) { ?>
				$.prettyPhoto.open('#inlineContent','','');
			<?php } ?>
			// $.prettyPhoto.close();
		});
		$(window).scroll(function(){
			$("#categoriesMenu").css({
				position: 'relative',
				top: 0
			});
			
			$("#yourOrder").css({
				position: 'relative',
				top: 0
			});
			
			var scrollTop 		= $(document).scrollTop();
			var contentOffset 	= $('#categoriesMenu').offset().top;
			var contentHeight 	= $('#categoriesMenu').outerHeight();
			
			var menuCategoriesOffset 	= $("#categoriesMenu").offset().top;
			var menuCategoriesHeight 	= $("#categoriesMenu").outerHeight();
			var menuCategoriesTooLow 	= contentOffset + contentHeight - scrollTop < menuCategoriesHeight;
			var menuCategoriesTooHigh 	= scrollTop > menuCategoriesOffset;
			
			if (menuCategoriesTooLow || menuCategoriesTooHigh) {
				$("#categoriesMenu").css('position', 'fixed');
				$("#yourOrder").css('position', 'fixed');
			}
			if (menuCategoriesTooLow) {
				$("#categoriesMenu").css('position', 'fixed');
				$("#yourOrder").css('position', 'fixed');
				// $("#categoriesMenu").css('top', contentOffset + contentHeight - scrollTop - menuCategoriesHeight);
			}
		});
	</script>
	<?php if (strstr($_SERVER['HTTP_USER_AGENT'],"Chrome")) { ?>
	<style type="text/css">
        .menu ul strong {margin-left:50px;}
    </style>
    <?php } ?>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="restaurant.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>"><?php echo $res['name']; ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Menu</a>
                </div>
            </div>
        </div>
        <div class="row product-row">
        	<div class="span2">
            	<div class="product-img"><img src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" /></div>
            </div>
            <div class="span6">
            	<div class="row ">
                	<div class="span6 product-name">
                    	<h2><?php echo $res['name']; ?></h2>
                        <p>
                            
                            
                            <?php
                                if ($res['minimum_order'] != NULL) {
                                    echo '<strong>Minimum Order:</strong> RMB '.$res['minimum_order'].'<br>'; 
                                }
                                if ($res['delivery_fee'] != NULL) {
                                    echo '<strong>Delivery Fee:</strong> RMB '.$res['delivery_fee'].'<br>'; 
                                }
                            ?>
                            <?php __($res['address']); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                	<div class="span3 type-food">
                    	<h3>Type of Food</h3>
                        <?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['id']} AND r.status=1 AND c.status=1"); ?>
                        <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = $rcr['title']; ?>
						<p><?php echo implode(", ",$cuisines); ?></p>
					</div>
                    <div class="span3 pull-right type-food">
                    	<h3>Delivery Time</h3>
						<?php $del_hours = deliveryHours($res['id'], true); ?>
						<p><?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?></p>
                    </div>
                </div>
            </div>
            <div class="span4 starsImagine">
            	<div class="view-menu"><a class="yellow-button" href="#">View menu</a></div>
                <?php $rating = ratings($res['id']); ?>
                <p class="user-rating">User rating (<a href="restaurant.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>#ratings"><?php echo $rating['count']; ?> ratings</a>)</p>
                <span class="rating">
                    <?php 
                        for($i=$rating['rating']; $i<=4; $i++) { 
                            echo '<span class="star"></span>';
                        } // endfor;
                        for($i=1; $i<=$rating['rating']; $i++) { 
                            echo '<span class="star rated"></span>';
                        } // endfor; 
                    ?>
                </span>
            </div>
        </div>
        <div class="row">
        	<div class="span3 my-span-categories">
                <div class="block-page categories" id="categoriesMenu" style="width:190px;">
                    <h2>Categories</h2>
                    <p>Skip to your favourite section by clicking on a menu category: <span></span></p>
                    <ul>
                    	<?php $cats = $db->query("SELECT * FROM menu_categories WHERE restaurant_id={$res['id']} AND status=1 ORDER BY prior ASC"); ?>
                        <?php while($cr=$db->fetch_array($cats)) { ?>
	                        <li><a href="#<?php echo $cr['id']; ?>"><?php echo $cr['title']; ?></a></li>
                        <?php } // while $cats loop ?>
                     </ul>
                </div>
            </div>
            
            <div class="span7">
            	<div class="block-page menu">
                	<h2>Menu</h2>
					<?php $cats = $db->query("SELECT * FROM menu_categories WHERE restaurant_id={$res['id']} AND status=1 ORDER BY prior ASC"); ?>
                    <?php while($cat=$db->fetch_array($cats)) { ?>
                    <a name="<?php echo $cat['id']; ?>"></a>
                    <span><?php echo $cat['title']; ?></span>
                    <p><?php echo $cat['description']; ?></p>
                    <ul>
                    	<div class="price">Price</div>
                    	<?php $items = $db->query("SELECT * FROM menu_items WHERE menu_cat_id={$cat['id']} AND status=1 ORDER BY item_number"); ?>
                        <?php while($item=$db->fetch_array($items)) { ?>
                        <li>
                        	<div class="item-info-container">
                                <p><b><?php echo $item['item_number']; ?>.</b> <?php echo $item['name']; ?></p>
                                <strong><?php echo $item['description']; ?></strong>
                            </div>
                            <div class="strock">
								<?php if ($item['price']!='0.00') { ?>
                                    <div class="block">
                                        <span><?php echo $item['value']; ?></span>
                                        <i><?php echo $item['price']; ?><a href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $item['id']; ?>&size=0&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>
                                        <b><?php echo _priceSymbol; ?></b>
                                    </div>
                                <?php } ?>
                                
                                <?php $menuItem_Sizes = $db->query("SELECT * FROM menu_item_sizes WHERE menu_item_id={$item['id']}"); ?>
								 <?php if ($db->affected_rows > 0) { ?>
                                    <?php while($menuItem_Size=$db->fetch_array($menuItem_Sizes)) { ?>
                                        <div class="block">
                                            <span><?php echo $menuItem_Size['value']; ?></span>
                                            <i><?php echo $menuItem_Size['price']; ?><a href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $menuItem_Size['menu_item_id']; ?>&size=<?php echo $menuItem_Size['id']; ?>&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>
                                            <b><?php echo _priceSymbol; ?></b>
                                        </div>
                                    <?php } // while $menuItem_Size loop ?>
                                <?php } // if $db->affected_rows ?>
                            </div>
                        </li>
                        <?php } // whlie $items loop ?>
                    </ul>
            		<span class="seperator"></span>
                    <?php } // whlie $cats loop ?>
                </div>
            </div>
            <?php include('includes/sidebar-order.php'); ?>
        </div>
    </div>
    <?php //include('includes/footer.php'); ?>

    <div id="inlineContent" class="castomer-reviews hide">
        <br />
        <h2>This restaurant is currently not accepting orders. Please check the delivery hours.</h2>
        <br />
        <a class="yellow-button fleft" href="#" onclick="$.prettyPhoto.close();">Continue</a>
    </div>


</body>
</html>
