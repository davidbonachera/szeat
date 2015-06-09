<?php $xml = simplexml_load_file("pages/rate-takeaway/content.xml"); ?>
<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php"><?php echo ($xml->$lang->homm==""?$xml->en->homm:$xml->$lang->homm); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="account-details.php?tab=my-recent-orders"><?php echo ($xml->$lang->myorrd==""?$xml->en->myorrd:$xml->$lang->myorrd); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php echo ($xml->$lang->rateyour==""?$xml->en->rateyour:$xml->$lang->rateyour); ?></a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<!-- <div class="span12"> -->
            	<div class="block-page your-takeaway">
                    <h3 class="page-header"><?php echo ($xml->$lang->rateyour==""?$xml->en->rateyour:$xml->$lang->rateyour); ?></h3>
                    <table width="560">
                        <thead>
                            <tr>
                                <th align="left"width="25%"><?php echo ($xml->$lang->date==""?$xml->en->date:$xml->$lang->date); ?> </th>
                                <th align="left"width="25%"><?php echo ($xml->$lang->Ordernumber==""?$xml->en->Ordernumber:$xml->$lang->Ordernumber); ?></th>
                                <th align="left"width="25%"><?php echo ($xml->$lang->Restaurant==""?$xml->en->Restaurant:$xml->$lang->Restaurant); ?></th>
                                <th align="left"width="25%"><?php echo ($xml->$lang->Price==""?$xml->en->Price:$xml->$lang->Price); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo date($datecountry,strtotime($order['date'])); ?></td>
                                <td class="red"><a href="order-summary.php?order=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                                
                                <?php ($lang=='cn'?$name='name_cn':$name='name'); ?>
                                <td nowrap><?php echo (getData('restaurants',$name,$order['restaurant_id'])==""?getData('restaurants','name',$order['restaurant_id']):getData('restaurants',$name,$order['restaurant_id'])); ?> &nbsp; </td>

                                <td><?php echo $order['price']; ?> <?php echo _priceSymbol; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                    	<?php echo ($xml->$lang->didyou==""?$xml->en->didyou:$xml->$lang->didyou); ?>
                    </p>
                    <p>
                    	<?php echo ($xml->$lang->usethe==""?$xml->en->usethe:$xml->$lang->usethe); ?>
                    </p>
                    <p>
                    	<?php echo ($xml->$lang->note==""?$xml->en->note:$xml->$lang->note); ?>
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
                                <strong><?php echo ($xml->$lang->starrat==""?$xml->en->starrat:$xml->$lang->starrat); ?></strong>
                                <span class="rating" id="stars">
                                    <span class="star" id="star5"></span><span class="star" id="star4"></span><span class="star" id="star3"></span><span class="star" id="star2"></span><span class="star" id="star1"></span>
                                </span>
                            </div>
                            <form action="" method="post">
                                <label><?php echo ($xml->$lang->comm==""?$xml->en->comm:$xml->$lang->comm); ?></label>
                                <textarea name="comments" id="comments"></textarea>
                                <input type="hidden" name="rating" id="rating" value="0" />
                                <button type="submit" class="yellow-button"><?php echo ($xml->$lang->saven==""?$xml->en->saven:$xml->$lang->saven); ?></button>
                            </form>
                        </div>
                    <?php } else { // $db->affected_rows ?>
                    	<div class="rate-your-star">
                            <div class="rate-take-container">
                                <strong><?php echo ($xml->$lang->urrat==""?$xml->en->urrat:$xml->$lang->urrat); ?></strong>
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
                                <label><?php echo ($xml->$lang->urcomm==""?$xml->en->urcomm:$xml->$lang->urcomm); ?></label>
                                <p><?php echo $cr['comments']; ?></p>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            <!-- </div> -->
        </div>
    	<!-- end of main content -->
    </div>