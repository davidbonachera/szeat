<div class="container">

    	<div class="col-sm-12">

        	<div class="page-header">

                <div class="col-sm-8">

            	    <a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><span id="cuisineCount"><?php echo checkFeild($cuisineName) ? countCuisines($cuisines):$resultCount; ?></span> <span id="cuisineName"><?php echo $cuisineName; ?></span> takeaways serving <?php echo isset($buildingName) ? $buildingName.(isset($areaName) ? ' in '.$areaName:NULL):NULL; ?></a>

            	</div>

                <div class="col-sm-4">
                    
                    <form method="get" action="" class="pull-right form-inline" id="sortForm">
                        <div class="form-group">
                            <label style="margin-right:1em;">Sort by</label>
                            
                            <?php
                                foreach ($_GET as $key=>$val) {    
                                    if ($key!="sort") {
                                        echo '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
                                    }
                                } 
                            ?>

                            <select name="sort" id="sort" class="chosen-select form-control" onchange="this.form.submit()">
                                <option value="">Select</option>
                                <option value="best"    <?php echo $_GET['sort']=='best'    ? 'selected':NULL; ?>>Best Match</option>
                                <option value="new"     <?php echo $_GET['sort']=='new'     ? 'selected':NULL; ?>>Newest First</option>
                                <option value="rating"  <?php echo $_GET['sort']=='rating'  ? 'selected':NULL; ?>>User Rating</option>
                                <option value="name"    <?php echo $_GET['sort']=='name'    ? 'selected':NULL; ?>>Restaurant Name</option>
                            </select>
                        </div>
                    </form>
                    
                </div>
            
            </div>
        </div>
    
    <div class="row">
    	<?php require_once('includes/sidebar-search.php'); ?>
        <div class="col-sm-9 product-list">
        	<ul class="product-list-items">
            	<?php while ($res=$db->fetch_array($search)) : ?>
                <?php $cuisineIds = explode(', ',$res['cuisines']); ?>
                <li class="<?php foreach ($cuisineIds as $cuisine) echo $cuisine.' '; ?>">
                	<div class="row product-strock">
                		<div class="col-sm-2">	
                        	<a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><img class="img-responsive" src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" /></a>
                        </div>
                        <div class="col-sm-7">
                        	<div class="product-name-list">
                            	<h2><a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php __($res['name']); ?></a></h2>
                                
                                    <?php
                                        if ($res['minimum_order'] != NULL) {
                                            echo '<strong>Minimum Order:</strong> RMB '.$res['minimum_order'].'<br>'; 
                                        }
                                        if ($res['delivery_fee'] != NULL) {
                                            echo '<strong>Delivery Fee:</strong> RMB '.$res['delivery_fee'].'<br>'; 
                                        }
                                    ?>
                                    
                                    <i><?php __($res['address']); ?></i>
                                    <br><br>    
                                
                            </div>
                            <div class="product-type-list">
                            	
                            	<strong>Type of Food: </strong>
								<?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                                <?php $cuisines2 = array(); ?>
                                <?php while ($rcr=$db->fetch_array($rc)) $cuisines2[] = $rcr['title']; ?>
                                <?php echo implode(", ",$cuisines2); ?>
                                
                                <br>
                            
                                <strong>Delivery Hours: </strong>
                                <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
                                <?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
                            
                            </div>
                        </div>
                        <div class="text-center col-sm-3 product-button-list">
                        	<div class="view-menu"><a class="btn btn-yellow" href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>">View menu</a></div>
                            <?php $rating = ratings($res['restaurant_id']); ?>
                            <b>User Rating:</b><br>(<a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>#ratings"><?php echo $rating['count']; ?> ratings</a>)
                            

                            <div class="rating">
                            	<?php 
									for($i=$rating['rating']; $i<=4; $i++) { 
										echo '<span class="star"></span>';
									}
									for($i=1; $i<=$rating['rating']; $i++) { 
										echo '<span class="star rated"></span>';
									} 
								?>
                            </p>
                            </div>
                        </div>    
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>