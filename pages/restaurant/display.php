<?php $xml = simplexml_load_file("pages/restaurant/content.xml"); ?>
<div class="container main">

	<div class="row">
    	<div class="col-xs-12">
        	<div class="page-header">
            	<a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
                <img src="img/title-icon.png" alt="" />
                <?php ($lang=='cn'?$title='title_cn':$title='title'); ?>
                <a href="#"><?php echo (getData('areas',$title,$res['area_id'])==""?getData('areas','title',$res['area_id']):getData('areas',$title,$res['area_id'])); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?></a>
            </div>
        </div>
    </div>

    <div class="row product-row">

        <div class="col-sm-2">
            <img class="center img-responsive" src="timthumb.php?w=111&zc=0&src=./<?php echo checkFeild($res['thumbnail']) ? $res['thumbnail']:'img/no_image_thumb.gif'; ?>" alt="" />
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
                <?php $rc = $db->query("SELECT r.*,c.* FROM `restaurants_cuisines` AS r LEFT JOIN cuisines AS c ON r.`cuisine_id`=c.id WHERE r.restaurant_id={$res['restaurant_id']} AND r.status=1 AND c.status=1"); ?>
                <?php while ($rcr=$db->fetch_array($rc)) $cuisines[] = ($lang=='cn'?($rcr['title_cn']==""?$rcr['title']:$rcr['title_cn']):$rcr['title']); ?>
               
                    <?php $comma = ($lang == 'cn' ? "、 " : ", "); ?>
                    <?php echo implode($comma,$cuisines); ?>

                <br>
                <strong><?php echo ($xml->$lang->delltime==""?$xml->en->delltime:$xml->$lang->delltime); ?>: </strong>
                <?php $del_hours = deliveryHours($res['restaurant_id'], true); ?>
                <?php __($del_hours['start']); ?> - <?php __($del_hours['end']); ?>
            </div>
        </div>

        <div class="col-sm-3 starsImagine">
            <div class="view-menu"><a class="btn btn-yellow" href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php echo ($xml->$lang->viewmenu==""?$xml->en->viewmenu:$xml->$lang->viewmenu); ?></a></div>
            <?php $rating = ratings($res['restaurant_id']); ?>
            <p class="user-rating"><?php echo ($xml->$lang->usarat==""?$xml->en->usarat:$xml->$lang->usarat); ?> (<a href="#ratings"><?php echo $rating['count']; ?> <?php echo ($xml->$lang->ratingsnum==""?$xml->en->ratingsnum:$xml->$lang->ratingsnum); ?></a>)</p>
            <span class="rating">
                <?php 
                    for($i=$rating['rating']; $i<=4; $i++) { 
                        echo '<span class="star"></span>';
                    }
                    for($i=1; $i<=$rating['rating']; $i++) { 
                        echo '<span class="star rated"></span>';
                    } 
                ?>
            </span>
        </div>

    </div>


    <div class="row">
        <div class="col-md-8">
            <div class="block-page more-about">
                <h2><?php echo ($xml->$lang->moreabout==""?$xml->en->moreabout:$xml->$lang->moreabout); ?> <?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?></h2>
                <p>
                    <?php echo $res['description']; ?>
                    <a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['restaurant_id']; ?>"><?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?> <?php echo ($xml->$lang->menu==""?$xml->en->menu:$xml->$lang->menu); ?></a>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="block-page helivery-houers">
                <h2><?php echo ($xml->$lang->delhrs==""?$xml->en->delhrs:$xml->$lang->delhrs); ?></h2>
                <ul>
                    <?php $del_hours = deliveryHours($res['restaurant_id']); ?>
                    <?php if (sizeof($del_hours) > 0) { ?>
                        <?php foreach ($del_hours as $dr) { ?>
                        <?php // echo $dr['day']; ?>
                            <?php if ($dr['day']=='MONDAY') $chinkDay = '星期一'; ?>
                            <?php if ($dr['day']=='TUESDAY') $chinkDay = '星期二'; ?>
                            <?php if ($dr['day']=='WEDNESDAY') $chinkDay = '星期三'; ?>
                            <?php if ($dr['day']=='THURSDAY') $chinkDay = '星期四'; ?>
                            <?php if ($dr['day']=='FRIDAY') $chinkDay = '星期五'; ?>
                            <?php if ($dr['day']=='SATURDAY') $chinkDay = '星期六'; ?>
                            <?php if ($dr['day']=='SUNDAY') $chinkDay = '星期天'; ?>
                            <li class="clearfix"><label><?php echo ($lang=='cn'?$chinkDay:ucfirst(strtolower($dr['day']))); ?></label> <?php echo $dr['start']; ?> - <?php echo $dr['end']; ?></li>
                        <?php } // foreach $del_hours ?>
                    <?php } // sizeof($del_hours) ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 castomer-reviews">
            <h2><a name="ratings"></a><?php echo ($xml->$lang->userrev==""?$xml->en->userrev:$xml->$lang->userrev); ?> <?php echo ($lang=='cn'?($res['name_cn']==""?$res['name']:$res['name_cn']):$res['name']); ?></h2>
            <?php $ratings = $db->query("SELECT * FROM ratings WHERE restaurant_id={$res['restaurant_id']} AND status=1 ORDER BY date DESC, id DESC"); ?>
            <?php while ($rr=$db->fetch_array($ratings)) { ?>
            <div class="customer-reviews">
                <div class="castomer-reviews-stars">
                    <p>
                        <i><?php echo ($xml->$lang->overall==""?$xml->en->overall:$xml->$lang->overall); ?></i>
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
                        <b><?php echo reset($exploded); ?><?php echo ($xml->$lang->from==""?$xml->en->from:$xml->$lang->from); ?> <?php echo getData('areas','title', getData('users','area_id',$rr['user_id'])); ?></b>
                     </p>
                     <em><?php echo ($xml->$lang->date==""?$xml->en->date:$xml->$lang->date); ?>: <?php echo date("d-M-Y",strtotime($rr['date'])); ?></em>
                </div>
                <i><?php echo $rr['comments']; ?></i>
            </div>
            <?php } // endwhile ?>
        </div>
    </div>





</div>