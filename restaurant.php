<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php 
if (isset($_GET['restaurant'])) {
	$rID = $db->escape($_GET['id']);
	$res = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.*, rb.* FROM restaurants AS r
							 LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
							 LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id 
							 WHERE r.id='$rID' AND r.status=1 LIMIT 1");
}elseif (isset($_GET['restaurant'])) {
	$str = str_replace(array('_','+'), array('&',' '), html_entity_decode($_GET['restaurant']));
	$res = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.*, rb.* FROM restaurants AS r
							 LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
							 LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id 
							 WHERE name LIKE '%$str%' AND r.status=1 LIMIT 1");
} else {
	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo htmlentities($res['name']); ?>  <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
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
                    <a href="#"> <?php echo getData('areas','title', $res['area_id']); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php echo $res['name']; ?></a>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="span10">
            	<div class="row product-row">
                    <div class="span2">
                        <div class="product-img"><img src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" /></div>
                    </div>
                    <div class="span5">
                        <div class="row ">
                            <div class="span5 product-name">
                                <h2><?php echo $res['name']; ?></h2>
                        		<p><?php echo $res['address']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3 type-food">
                                <h3>Type of food</h3>
								<?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                                <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = $rcr['title']; ?>
                                <p><?php if (sizeof($cuisines) > 0) echo implode(", ",$cuisines); ?></p>
                            </div>
                            <div class="span2 pull-right type-food">
                                <h3>Delivery Time</h3>
                                <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
								<p><?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="span3 restaurant-view">
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
                <div class="row">
                	<div class="span7">
                        <div class="block-page more-about">
                            <h2>More About <?php __($res['name']); ?></h2>
                            <p>
                            	<?php echo $res['description']; ?>
                                <a href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php __($res['name']); ?> Menu</a>
                            </p>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="block-page helivery-houers">
                            <h2>Delivery Hours</h2>
                            <ul>
                                <?php $del_hours = deliveryHours($res['restaurant_id']); ?>
                                <?php if (sizeof($del_hours) > 0) { ?>
									<?php foreach ($del_hours as $dr) { ?>
                                        <li class="clearfix"><label><?php echo ucfirst(strtolower($dr['day'])); ?></label> <?php echo $dr['start']; ?> - <?php echo $dr['end']; ?></li>
                                    <?php } // foreach $del_hours ?>
                                <?php } // sizeof($del_hours) ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                	<div class="span10 castomer-reviews">
                    	<h2><a name="ratings"></a>Customer reviews on <?php __($res['name']); ?></h2>
                        <?php $ratings = $db->query("SELECT * FROM ratings WHERE restaurant_id={$res['restaurant_id']} AND status=1 ORDER BY date DESC, id DESC"); ?>
                        <?php while ($rr=$db->fetch_array($ratings)) { ?>
                        <div class="customer-reviews">
                            <div class="castomer-reviews-stars">
                                <p>
                                    <i>Overall</i>
                                    <span class="rating">
                                        <?php 
                                            for($i=$rr['ratings']; $i<=4; $i++) { 
                                                echo '<span class="star"></span>';
                                            } // endfor;
                                            for($i=1; $i<=$rr['ratings']; $i++) { 
                                                echo '<span class="star rated"></span>';
                                            } // endfor; 
                                        ?>
                                    </span>
                                    <?php 
										$getUsers = getData('users','name',$rr['user_id']); 
										$exploded = explode(" ",$getUsers);
									?>
                                    <b><?php echo reset($exploded); ?> from <?php echo getData('areas','title', getData('users','area_id',$rr['user_id'])); ?></b>
                                 </p>
                                 <em>Date: <?php echo date("d-M-Y",strtotime($rr['date'])); ?></em>
                            </div>
                            <i><?php echo $rr['comments']; ?></i>
                        </div>
                        <?php } // endwhile ?>
                    </div>
                </div>
            </div>
        	<div class="span2">
            	<img src="img/keep-up.png" />
                <div class="soc-net"><a href="<?php echo checkFeild(_facebook) ? _facebook:'#'; ?>"><img src="img/fbook1.png" alt="" />Facebook</a></div>
                <div class="soc-net"><a href="<?php echo checkFeild(_twitter) ? _twitter:'#'; ?>"><img src="img/twiter1.png" alt="" />Twiter</a></div>
            </div>
        </div>
        
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>