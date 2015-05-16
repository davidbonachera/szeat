<div class="container main">

	<div class="row">
    	<div class="col-xs-12">
        	<div class="page-header">
            	<a href="index.php">Home</a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"> <?php echo getData('areas','title', $res['area_id']); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo $res['name']; ?></a>
            </div>
        </div>
    </div>

    <div class="row product-row">

        <div class="col-sm-2">
            <img class="center img-responsive" src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'images/no_image_thumb.gif'; ?>" alt="" />
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
                <?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = $rcr['title']; ?>
               <?php echo implode(", ",$cuisines); ?>
            
                <br>
                <strong>Delivery Time: </strong>
                <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
                <?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
            </div>
        </div>

        <div class="col-sm-3 starsImagine">
            <div class="view-menu"><a class="btn btn-yellow" href="#">View menu</a></div>
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
        <div class="col-md-8">
            <div class="block-page more-about">
                <h2>More About <?php __($res['name']); ?></h2>
                <p>
                    <?php echo $res['description']; ?>
                    <a href="menu.php?restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php __($res['name']); ?> Menu</a>
                </p>
            </div>
        </div>

        <div class="col-md-4">
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
        <div class="col-md-10 castomer-reviews">
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