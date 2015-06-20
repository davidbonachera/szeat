<?php $xml = simplexml_load_file("pages/menu/content.xml"); ?>
<div class="container main">
    	<div class="row">
        	<div class="col-xs-12">
            	<div class="page-header">
                	<a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>"><?php echo ($lang=='cn' ? ($_SESSION['user']['restaurant']['name_cn']=="" ? $_SESSION['user']['restaurant']['name'] : $_SESSION['user']['restaurant']['name_cn']) : $_SESSION['user']['restaurant']['name']); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php echo ($xml->$lang->menu==""?$xml->en->menu:$xml->$lang->menu); ?></a>
                </div>
            </div>
        </div>

        <div class="row product-row">
        	<div class="col-sm-2">
            	<img class="img-responsive" src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'img/no_image_thumb.gif'; ?>" alt="" />
            </div>

            <div class="col-sm-7">
            	<div class="product-name">
                	<h2><?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?></h2>
                    <i><?php echo ($lang=='cn'?($res['address_cn']==""?$res['address']:$res['address_cn']):$res['address']); ?></i>
                    <p>
                        <?php
                        if ($res['minimum_order'] >0) {
                            echo '<strong>'.($xml->$lang->minord==""?$xml->en->minord:$xml->$lang->minord).':</strong> RMB '.$res['minimum_order'].'<br>'; 
                        }
                        if ($res['delivery_fee'] >0) {
                            echo '<strong>'.($xml->$lang->dellfee==""?$xml->en->dellfee:$xml->$lang->dellfee).':</strong> RMB '.$res['delivery_fee'].'<br>'; 
                        }
                        ?>
                    </p>
                </div>
                
                <div class="product-type-list">
                	<strong><?php echo ($xml->$lang->typafood==""?$xml->en->typafood:$xml->$lang->typafood); ?>: </strong>
                    <?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['id']} AND r.status=1 AND c.status=1"); ?>
                    <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = ($lang=='cn'?($rcr['title_cn']==""?$rcr['title']:$rcr['title_cn']):$rcr['title']); ?>
			       
                    <?php $comma = ($lang == 'cn' ? "、 " : ", "); ?>
                    <?php echo implode($comma,$cuisines); ?>
				
                    <br>
                	<strong><?php echo ($xml->$lang->delltime==""?$xml->en->delltime:$xml->$lang->delltime); ?>: </strong>
					<?php $del_hours = deliveryHours($res['id'], true); ?>
					<?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
                </div>
            </div>

            <div class="col-sm-3 starsImagine">
            	<!-- <div class="view-menu"><a class="yellow-button" href="#">View menu</a></div> -->
                <?php $rating = ratings($res['id']); ?>
                <p class="user-rating"><?php echo ($xml->$lang->usarat==""?$xml->en->usarat:$xml->$lang->usarat); ?> (<a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>#ratings"><?php echo $rating['count']; ?> <?php echo ($xml->$lang->ratingsnum==""?$xml->en->ratingsnum:$xml->$lang->ratingsnum); ?></a>)</p>
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
                    <h2><?php echo ($xml->$lang->bigcats==""?$xml->en->bigcats:$xml->$lang->bigcats); ?></h2>
                    <p><?php echo ($xml->$lang->skippy==""?$xml->en->skippy:$xml->$lang->skippy); ?>: <span></span></p>
                    <ul>
                    	<?php $cats = $db->query("SELECT * FROM menu_categories WHERE restaurant_id={$res['id']} AND $status=1 ORDER BY prior ASC"); ?>
                        <?php while($cr=$db->fetch_array($cats)) { ?>
	                        <li><a href="#<?php echo $cr['id']; ?>"><?php echo ($lang=='cn'?($cr['title_cn']==""?$cr['title']:$cr['title_cn']):$cr['title']); ?></a></li>
                        <?php } // while $cats loop ?>
                     </ul>
                </div>
            </div>
            
            <div class="col-sm-6">
            	<div class="block-page menu">
                	<h2><?php echo ($xml->$lang->bigmenu==""?$xml->en->bigmenu:$xml->$lang->bigmenu); ?></h2>
					<?php $cats = $db->query("SELECT * FROM menu_categories WHERE restaurant_id={$res['id']} AND $status=1 ORDER BY prior ASC"); ?>
                    <?php while($cat=$db->fetch_array($cats)) { ?>
                    <a name="<?php echo $cat['id']; ?>"></a>
                    <span><?php echo ($lang=='cn'?($cat['title_cn']==""?$cat['title']:$cat['title_cn']):$cat['title']); ?></span>
                    <p><?php echo ($lang=='cn'?($cat['description_cn']==""?$cat['description']:$cat['description_cn']):$cat['description']); ?></p>
                    <ul>
                    	<div class="price"><?php echo ($xml->$lang->bigprice==""?$xml->en->bigprice:$xml->$lang->bigprice); ?></div>
                    	<?php $items = $db->query("SELECT * FROM menu_items WHERE menu_cat_id={$cat['id']} AND $status=1 ORDER BY item_number"); ?>
                        <?php while($item=$db->fetch_array($items)) { ?>
                        <li>
                        	<div class="item-info-container">
                                <p><b><?php echo $item['item_number']; ?>.</b> <?php echo ($lang=='cn'?($item['name_cn']==""?$item['name']:$item['name_cn']):$item['name']); ?></p>
                                <strong><?php echo ($lang=='cn'?($item['description_cn']==""?$item['description']:$item['description_cn']):$item['description']); ?></strong>
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
                                            <span><?php echo ($lang=='cn'?($menuItem_Size['value_cn']==""?$menuItem_Size['value']:$menuItem_Size['value_cn']):$menuItem_Size['value']); ?></span>
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
            <?php include('pages/menu/sidebar-order.php'); ?>
        </div>
    </div>

    <?php 

    if ($deliveryAvailable==false) { ?>
<div class="modal fade" id="restoclosedmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo ($xml->$lang->closed==""?$xml->en->closed:$xml->$lang->closed); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo ($xml->$lang->notaccepting==""?$xml->en->notaccepting:$xml->$lang->notaccepting); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ($xml->$lang->viewmenu==""?$xml->en->viewmenu:$xml->$lang->viewmenu); ?></button>
                    <a href="javascript:history.back()" type="button" class="btn btn-primary"><?php echo ($xml->$lang->bakk==""?$xml->en->bakk:$xml->$lang->bakk); ?></a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php }

    