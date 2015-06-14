<?php $xml = simplexml_load_file("pages/search/content.xml"); ?>
<div class="container">

    	<div class="col-sm-12">

        	<div class="page-header">

                <div class="col-sm-8">

            	    <a href="index.php"><?php echo ($xml->$lang->home == "" ? $xml->en->home : $xml->$lang->home); ?></a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><span id="cuisineCount"><?php echo checkFeild($cuisineName) ? countCuisines($cuisines):$resultCount; ?></span> <span id="cuisineName"><?php echo ($lang=='cn'?($chinesecuisineName=""?$cuisineName:$chinesecuisineName):$cuisineName); ?></span> <?php echo ($xml->$lang->serving == "" ? $xml->en->serving : $xml->$lang->serving); ?> <?php echo isset($buildingName) ? ($lang=='cn'?($chinesebuildingName==""? $buildingName : $chinesebuildingName) : $buildingName).(isset($areaName) ? ' '.($xml->$lang->in == "" ? $xml->en->in : $xml->$lang->in).' '.($lang=='cn'?($chineseareaName==""?$areaName:$chineseareaName):$areaName):NULL):NULL; ?></a>

            	</div>

                <div class="col-sm-4">
                    
                    <form method="get" action="" class="pull-right form-inline" id="sortForm">
                        <div class="form-group">
                            <label style="margin-right:1em;"><?php echo ($xml->$lang->sortby == "" ? $xml->en->sortby : $xml->$lang->sortby); ?></label>
                            
                            <?php
                                foreach ($_GET as $key=>$val) {    
                                    if ($key!="sort") {
                                        echo '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
                                    }
                                } 
                            ?>

                            <select name="sort" id="sort" class="chosen-select form-control" onchange="this.form.submit()">
                                <option value=""><?php echo ($xml->$lang->select == "" ? $xml->en->select : $xml->$lang->select); ?></option>
                                <option value="best"    <?php echo $_GET['sort']=='best'    ? 'selected':NULL; ?>><?php echo ($xml->$lang->bestmatch == "" ? $xml->en->bestmatch : $xml->$lang->bestmatch); ?></option>
                                <option value="new"     <?php echo $_GET['sort']=='new'     ? 'selected':NULL; ?>><?php echo ($xml->$lang->newestfirst == "" ? $xml->en->newestfirst : $xml->$lang->newestfirst); ?></option>
                                <option value="rating"  <?php echo $_GET['sort']=='rating'  ? 'selected':NULL; ?>><?php echo ($xml->$lang->userrating == "" ? $xml->en->userrating : $xml->$lang->userrating); ?></option>
                                <option value="name"    <?php echo $_GET['sort']=='name'    ? 'selected':NULL; ?>><?php echo ($xml->$lang->restoname == "" ? $xml->en->restoname : $xml->$lang->restoname); ?></option>
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
                            	<h2><a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php echo urlText($pr['id']); ?><?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?></a></h2>
                                <i><?php echo ($lang=='cn'?($res['address_cn']==""?$res['address']:$res['address_cn']):$res['address']); ?></i>
                                <?php
                                    if ($res['minimum_order'] >0) {
                                        echo '<br><strong>'.($xml->$lang->minord == "" ? $xml->en->minord : $xml->$lang->minord).':</strong> RMB '.$res['minimum_order']; 
                                    }
                                    if ($res['delivery_fee'] >0) {
                                        echo '<br><strong>'.($xml->$lang->dellfee == "" ? $xml->en->dellfee : $xml->$lang->dellfee).':</strong> RMB '.$res['delivery_fee']; 
                                    }
                                ?>
                                
                                
                                <br><br>    
                                
                            </div>
                            <div class="product-type-list">
                            	
                            	<strong><?php echo ($xml->$lang->typafood == "" ? $xml->en->typafood : $xml->$lang->typafood); ?>: </strong>
								<?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                                <?php $cuisines2 = array(); ?>
                                <?php //while ($rcr=$db->fetch_array($rc)) $cuisines2[] = $rcr['title']; ?>
                                <?php while ($rcr=$db->fetch_array($rc)) $cuisines2[] = ($lang=='cn'?($rcr['title_cn']==""?$rcr['title']:$rcr['title_cn']):$rcr['title']); ?>
                                
                                <?php $comma = ($lang == 'cn' ? "、 " : ", "); ?>
                                <?php echo implode($comma,$cuisines2); ?>
                                
                                <br>
                            
                                <strong><?php echo ($xml->$lang->delltime == "" ? $xml->en->delltime : $xml->$lang->delltime); ?>: </strong>
                                <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
                                <?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
                            
                            </div>
                        </div>
                        <div class="text-center col-sm-3 product-button-list">
                        	<div class="view-menu"><a class="btn btn-yellow" href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php echo ($xml->$lang->viewmenu == "" ? $xml->en->viewmenu : $xml->$lang->viewmenu); ?></a></div>
                            <?php $rating = ratings($res['restaurant_id']); ?>
                            <b><?php echo ($xml->$lang->usarat == "" ? $xml->en->usarat : $xml->$lang->usarat); ?>:</b><br>(<a href="index.php?page=restaurant&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>#ratings"><?php echo $rating['count']; ?> <?php echo ($xml->$lang->ratingsnum == "" ? $xml->en->ratingsnum : $xml->$lang->ratingsnum); ?></a>)
                            

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