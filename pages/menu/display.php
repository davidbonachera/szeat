<style>
body{ background:url(bg.jpg)}
.outdiv{background: hsl(0, 0%, 0%) none repeat scroll 0 0;
    bottom: 0;
    height: 100%;
    opacity: 0.6;
    position: fixed;
    top: 0;
    width: 100%;}
	
.div_in{margin:0; position:relative; z-index:999;}
.div_in_in{ background: hsl(0, 0%, 100%) none repeat scroll 0 0;
    margin: auto;
    min-height: 178px;
    padding: 0;
    width: 305px; mix-height:480px; overflow:auto;}

.div_in_in h5{  color: hsl(357, 84%, 43%);
    font-size: 25px;
    margin: 8px 0;
    text-align:left;
}
.div_in_in p{  color: #000;
    font-size: 15px;
    margin: 16px 0;
    text-align:left;
}
.div_in_in1{ background: hsl(0, 0%, 100%) none repeat scroll 0 0;
    margin: auto;
    min-height: 178px;
    padding:6px 11px;
    width: 330px; mix-height:480px; overflow:auto;}

.div_in_in1 h5{  color: hsl(357, 84%, 43%);
    font-size: 25px;
    margin: 8px 0;
    text-align:left;
}
.div_in_in1 p{  color: #000;
    font-size: 15px;
    margin: 16px 0;
    text-align:left;
}
form {
    margin: 0;
}
.control{
   float: left;
    width: 214px;
}
.control01{
       float: right;
    width: 89px;
}

#LocationPrompt input {
    border: 1px solid hsl(358, 56%, 89%);
    border-radius: 2px;
    font-size: 1.4rem;
    height: 42px;
    margin: 0;
    min-width: 10rem;
    padding: 0 6px;
    width: 90%;
}
.button{-moz-user-select: none;
    background-color: hsl(41, 99%, 66%);
    border: medium none;
    border-radius: 2px;
    box-sizing: border-box;
    color: hsl(0, 0%, 20%) !important;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
  
    line-height: 1;
    max-width: 300px;
    padding: 11px 17px;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    white-space: nowrap;
    width: 100%;}
	
	
	.button2{-moz-user-select: none;
    background-color: hsl(41, 27%, 66%);
    border: medium none;
    border-radius: 2px;
    box-sizing: border-box;
    color: hsl(0, 0%, 20%) !important;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
  
    line-height: 1;
    max-width: 300px;
    padding: 11px 17px;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    white-space: nowrap;
    width: 100%;}
	.button_none{
		-moz-user-select: none;
    border: medium none;
    border-radius: 2px;
    box-sizing: border-box;
    color: hsl(0, 0%, 20%) !important;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
  
    line-height: 1;
    max-width: 300px;
    padding: 4px 10px;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    white-space: nowrap;
    width: 100%;
	}
	.button1{-moz-user-select: none;
    background-color: hsl(41, 99%, 66%);
    border: medium none;
    border-radius: 2px;
    box-sizing: border-box;
    color: hsl(0, 0%, 20%) !important;
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
  
    line-height: 1;
    max-width: 300px;
    padding: 4px 10px;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    white-space: nowrap;
    width: 100%;}
	
	.regex{width:200px; float:left; height:30px;}
	.h_top_heding{background:#900; height:auto; float:left; width:100%; color:#fff;}
	.h_top_heding h5{color:#fff; text-align:center;}
	.right{float:right;}
	.addItem{width:100%; float:left}
	.item{width:130px; float:left}
	.price{width:; float:left}
	.body_in{float:left; width:100%; padding:10px;}
	.body_in ul li{list-style:none}
	.body_in ul{padding:0}
	.li_sty_fist{background:#f0f7fd; float:left; width:100%; padding:7px 0}
	.li_sty_fist_last{background:#fff; float:left; width:100%; padding:7px 0}
	.li_sty_fist_last_misns{background:#d9dee3; float:left; width:100%; padding:7px 0}
	.cl_close{color: #fff; right:0; position: absolute; margin: 0px 157px; opacity: 1;}
	@media (min-width: 268px) and (max-width: 779px) 
	{
	.cl_close{color: #fff; right:0; position: absolute; margin: 0px 0; opacity: 1;}
	.div_in_in {
    background: hsl(0, 0%, 100%) none repeat scroll 0 0;
    margin: auto;
    min-height: 178px;
    overflow: auto;
    padding: 0;
    width: 100%;
}
	
	}
</style>

<?php $xml = simplexml_load_file("pages/menu/content.xml"); ?>
					<div id="myModal2" class="modal fade in" style="display:none" aria-hidden="false">
					<div class="outdiv"></div>
					 <div role="document" class="modal-dialog">
						<div class="div_in">
						
					<div class="div_in_in1"><button aria-label="Close" data-dismiss="modal" class="close" type="button" style="color: #000; position: absolute; margin: 0px -16px; opacity: 1;"><span aria-hidden="true">×</span></button>
					<h5>We need your postcode</h5>
					<p>So we can tell if this restaurant deliver to your address.</p>

								<div class="control">
									<input type="text"  class="regex">
								</div>
								<div class="control01">
									<button class="button" id="ValidateLocation">Check</button>
								</div>

					</div>
						   
					</div>

					</div><!-- /.modal-dialog -->
					  </div>
					 
  
		<div id="myModal1" class="modal fade in" style="display:none" aria-hidden="false">
								<div class="outdiv"></div>
									<div role="document" class="modal-dialog">
									  <div class="">

										<div class="div_in">
										
								<div class="div_in_in makelayer">
								<button aria-label="Close" data-dismiss="modal" class="close cl_close" type="button" style=""><span aria-hidden="true">×</span></button>
								

											
											


									   
								</div>

								</div>
										
										

									  </div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
				</div>
  
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
                        <?php while($item=$db->fetch_array($items)) {    //echo "<pre>"; print_r($item);         ?>
                        <li>
                        	<div class="item-info-container">
                                <p><b><?php echo $item['item_number']; ?>.</b> <?php echo ($lang=='cn'?($item['name_cn']==""?$item['name']:$item['name_cn']):$item['name']); ?></p>
                                <strong><?php echo ($lang=='cn'?($item['description_cn']==""?$item['description']:$item['description_cn']):$item['description']); ?></strong>
                            </div>
                            <div class="strock">
							
							
							
								<?php if ($item['price']!='0.00') { ?>
                                    <div class="block">
									
                                      <!--  <span><?php echo $item['value']; ?></span>-->
										
										
                                        <i>
										
										
										<?php echo $item['price']; ?>  <img  style="cursor: pointer;" class="show_popup" i="<?php echo urlText($res['name']); ?>" dir="<?php echo $item['id']; ?>" b="<?php echo $item['name']; ?>"  a="<?php echo 0;?>" s="<?php echo $res['id']; ?>" src="img/add.png" alt="" >
										
										
										</i>
			<!-- <i><?php echo $item['price']; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $item['id']; ?>&size=0&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>  -->
										
										
								<!--		 <a href="javascript:void(0);" onclick ="javascript:$('#myModal1').show();"  data-target="#myModal1" data-toggle="modal">open POP up</a>-->
										
                                        <b><?php echo _priceSymbol; ?></b>
                                    </div>
															<?php } ?>
                                
								
								
								
                                <?php $menuItem_Sizes = $db->query("SELECT * FROM menu_item_sizes WHERE menu_item_id={$item['id']}"); ?>
								 <?php if ($db->affected_rows > 0) { ?>
                                    <?php while($menuItem_Size=$db->fetch_array($menuItem_Sizes)) { ?>
                                     <!--   <div class="block">
                                            <span><?php echo ($lang=='cn'?($menuItem_Size['value_cn']==""?$menuItem_Size['value']:$menuItem_Size['value_cn']):$menuItem_Size['value']); ?></span>
                                            <i><?php echo $menuItem_Size['price']; ?><?php //echo $item['price']; ?>  <img class="show_popup" dir="<?php echo $item['id']; ?>" b="<?php echo $item['name']; ?>" src="img/add.png" alt="" ></i>
                                      <b><?php echo _priceSymbol; ?></b>
                                        </div>-->
										
										
										 <div class="block">
                                            <span><?php echo ($lang=='cn'?($menuItem_Size['value_cn']==""?$menuItem_Size['value']:$menuItem_Size['value_cn']):$menuItem_Size['value']); ?></span>
											
											
              <i><!--<?php echo $menuItem_Size['price']; ?><a   style="cursor: pointer;" href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $menuItem_Size['menu_item_id']; ?>&size=<?php echo $menuItem_Size['id']; ?>&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a>-->
			 <?php echo $menuItem_Size['price']; ?> <img  style="cursor: pointer;" class="show_popup" i="<?php echo urlText($res['name']); ?>" dir="<?php echo $menuItem_Size['menu_item_id']; ?>" b="<?php echo $item['name']; ?>" a="<?php echo $menuItem_Size['id'];?>"  s="<?php echo $res['id']; ?>" src="img/add.png" alt="" >
			  
			  </i>
											
											
											
											
											
											
                                      <b><?php echo _priceSymbol; ?></b>
                                        </div>
										
										
										
										
										
										
										
										
										
                                    <?php } // while $menuItem_Size loop ?>
                                <?php } // if $db->affected_rows ?>
                            </div>
                        </li>
                        <?php }  // whlie $items loop ?>
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
    <?php } ?>

 