<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>"><?php echo $res['name']; ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Menu</a>
                </div>
            </div>
        </div>

        <div class="row product-row">
        	<div class="col-sm-2">
            	<img class="img-responsive" src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" />
            </div>

            <div class="col-sm-7">
            	<div class="product-name">
                	<h2><?php echo $res['name']; ?></h2>
                    <i><?php __($res['address']); ?></i>
                    <p>
                        <?php
                        if ($res['minimum_order'] != NULL) {
                            echo '<strong>Minimum Order:</strong> RMB '.$res['minimum_order'].'<br>'; 
                        }
                        if ($res['delivery_fee'] != NULL) {
                            echo '<strong>Delivery Fee:</strong> RMB '.$res['delivery_fee'].'<br>'; 
                        }
                        ?>
                    </p>
                </div>
                
                <div class="product-type-list">
                	<strong>Type of Food: </strong>
                    <?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['id']} AND r.status=1 AND c.status=1"); ?>
                    <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = $rcr['title']; ?>
			       <?php echo implode(", ",$cuisines); ?>
				
                    <br>
                	<strong>Delivery Time: </strong>
					<?php $del_hours = deliveryHours($res['id'], true); ?>
					<?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
                </div>
            </div>

            <div class="col-sm-3 starsImagine">
            	<!-- <div class="view-menu"><a class="yellow-button" href="#">View menu</a></div> -->
                <?php $rating = ratings($res['id']); ?>
                <p class="user-rating">User rating (<a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>#ratings"><?php echo $rating['count']; ?> ratings</a>)</p>
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
        	<div class="col-sm-3">
                <div class="block-page categories">
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
            
            <div class="col-sm-6">
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
                                        <i><?php echo $item['price']; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $item['id']; ?>&size=0&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>
                                        <b><?php echo _priceSymbol; ?></b>
                                    </div>
                                <?php } ?>
                                
                                <?php $menuItem_Sizes = $db->query("SELECT * FROM menu_item_sizes WHERE menu_item_id={$item['id']}"); ?>
								 <?php if ($db->affected_rows > 0) { ?>
                                    <?php while($menuItem_Size=$db->fetch_array($menuItem_Sizes)) { ?>
                                        <div class="block">
                                            <span><?php echo $menuItem_Size['value']; ?></span>
                                            <i><?php echo $menuItem_Size['price']; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $menuItem_Size['menu_item_id']; ?>&size=<?php echo $menuItem_Size['id']; ?>&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>
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