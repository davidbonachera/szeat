<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php
if ($_GET) {
	$area 		= $db->escape($_GET['area']);
	$building 	= $db->escape($_GET['building']);
	$cuisines 	= $db->escape($_GET['cuisines']);
	
	if (checkFeild($area)) {
		$areaName = getData('areas','title',$area);
		$where = "WHERE ra.area_id=$area";
	}
	if (checkFeild($building)) {
		$buildingName = getData('buildings','title',$building);
		$where = isset($where) ? $where." AND rb.building_id=$building":"WHERE rb.building_id=$building";
	}
	if (checkFeild($cuisines)) {
		$cuisineName = getData('cuisines','title',$cuisines);
	} else {
		$cuisineName = NULL;
	}
	
	$where = isset($where) ? $where." AND r.status=1":"WHERE r.status=1";
	
	if (isset($_GET['sort'])) {
		if 	   ($_GET['sort']=='best') 		$orderBy = 'ORDER BY id ASC';
		elseif ($_GET['sort']=='new') 		$orderBy = 'ORDER BY date DESC';
		elseif ($_GET['sort']=='rating') 	$orderBy = 'ORDER BY ratings DESC';
		elseif ($_GET['sort']=='name') 		$orderBy = 'ORDER BY name ASC';
		else 								$orderBy = NULL;
	} else {
		$orderBy = NULL;
	}
	$searchQuery = "SELECT r.id AS restaurant_id, r.*, ra.area_id, rb.building_id, 
				   (
				   		SELECT GROUP_CONCAT(cuisine_id separator ', ') FROM restaurants_cuisines AS rc WHERE rc.restaurant_id=r.id
				   ) AS cuisines,
				   (
					   SELECT AVG(ratings) FROM `ratings` AS rr WHERE rr.restaurant_id=r.id
				   ) AS ratings
				   	FROM restaurants AS r 
				    LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
				    LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id
				    $where GROUP BY id $orderBy";

	$search = $db->query($searchQuery);
	$resultCount = $db->affected_rows;
	
} else {
	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
	});
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
                    <a href="#"><span id="cuisineCount"><?php echo checkFeild($cuisineName) ? countCuisines($cuisines):$resultCount; ?></span> <span id="cuisineName"><?php echo $cuisineName; ?></span> takeaways serving <?php echo isset($buildingName) ? $buildingName.(isset($areaName) ? ' in '.$areaName:NULL):NULL; ?></a>
                	<div class="change-location pull-right">
                        <article class="change-location-form">
                        	<form method="get" action="" id="sortForm">
                            	<?php foreach ($_GET as $key=>$val) { ?>
	                            	<?php if ($key!="sort") { ?>
                                    	<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
                                    <?php } ?>
                                <?php } ?>
                                <select name="sort" id="sort" class="" onchange="this.form.submit()">
                                    <option value="">Select</option>
                                    <option value="best" 	<?php echo $_GET['sort']=='best' 	? 'selected':NULL; ?>>Best Match</option>
                                    <option value="new"	 	<?php echo $_GET['sort']=='new' 	? 'selected':NULL; ?>>Newest First</option>
                                    <option value="rating" 	<?php echo $_GET['sort']=='rating' 	? 'selected':NULL; ?>>User Rating</option>
                                    <option value="name" 	<?php echo $_GET['sort']=='name' 	? 'selected':NULL; ?>>Restaurant Name</option>
                                </select>
                            </form>
                        </article> 
                        <label>Sort by</label>
                        <a href="index.php">change location</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        	<?php require_once('includes/sidebar-search.php'); ?>
            <div class="span9 product-list">
            	<ul class="product-list-items">
                	<?php while ($res=$db->fetch_array($search)) : ?>
                    <?php $cuisineIds = explode(', ',$res['cuisines']); ?>
                    <li class="<?php foreach ($cuisineIds as $cuisine) echo $cuisine.' '; ?>">
                    	<div class="row product-strock">
                    		<div class="span2">
                            	<div class="product-img">
                                	<a href="restaurant.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><img src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" /></a>
                                </div>
                            </div>
                            <div class="span5">
                            	<div class="row">
                                	<div class="span5 product-name-list">
                                    	<h2><a href="restaurant.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php __($res['name']); ?></a></h2>
                                        <p>
                                            <?php __($res['address']); ?>
                                            <br>
                                            <strong>Minimum Order:</strong> RMB<?php echo $res['minimum_order']; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row product-type-list">
                                	<div class="span3">
                                    	<strong>Type of Food</strong>
										<?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                                        <?php $cuisines2 = array(); ?>
                                        <?php while ($rcr=$db->fetch_array($rc)) $cuisines2[] = $rcr['title']; ?>
                                        <p><?php echo implode(", ",$cuisines2); ?></p>
                                    </div>
                                    <div class="span2">
                                        <strong>Delivery Hours</strong>
                                        <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
                                        <p><?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="span2 product-button-list">
                            	<div class="view-menu"><a class="yellow-button" href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>">View menu</a></div>
                                <?php $rating = ratings($res['restaurant_id']); ?>
                                <p class="user-rating">User rating (<a href="restaurant.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>#ratings"><?php echo $rating['count']; ?> ratings</a>)</p>
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
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>
