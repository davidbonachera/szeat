<?php $xml = simplexml_load_file("pages/order-details/content.xml"); ?>
<div class="container">

	<div class="col-xs-12">
    	<div class="page-header">
        	<a href="index.php"><?php echo ($xml->$lang->home==""?$xml->en->home:$xml->$lang->home); ?></a>
            <img src="img/title-icon.png" alt="" />
            <a href="#"><?php echo ($xml->$lang->resul==""?$xml->en->resul:$xml->$lang->resul); ?></a>
            <img src="img/title-icon.png" alt="" />
            <?php if (isset($_SESSION['user']['restaurant']['name'])) { ?>
                <a href="index.php?page=restaurant&restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo $_SESSION['user']['restaurant']['id']; ?>"><?php echo ($lang=='cn' ? ($_SESSION['user']['restaurant']['name_cn']=="" ? $_SESSION['user']['restaurant']['name'] : $_SESSION['user']['restaurant']['name_cn']) : $_SESSION['user']['restaurant']['name']); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="index.php?page=menu&restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo $_SESSION['user']['restaurant']['id']; ?>"><?php echo ($xml->$lang->bigmenu==""?$xml->en->bigmenu:$xml->$lang->bigmenu); ?></a>
                <img src="img/title-icon.png" alt="" />
            <?php } else { ?>
                <a href="#"><?php echo ($xml->$lang->resto==""?$xml->en->resto:$xml->$lang->resto); ?></a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo ($xml->$lang->bigmenu==""?$xml->en->bigmenu:$xml->$lang->bigmenu); ?></a>
                <img src="img/title-icon.png" alt="" />
            <?php } ?>
            <a href="#"><?php echo ($xml->$lang->delad==""?$xml->en->delad:$xml->$lang->delad); ?></a>
        </div>
    </div>

    <div class="row">
    	<div class="col-sm-6">
            <div class="well">
                
                <div class="myfonts"><h2><?php echo ($xml->$lang->urorder==""?$xml->en->urorder:$xml->$lang->urorder); ?></h2></div>
            	
                <div class="full-order-price-container">
                	<?php $total_price = 0; ?>
                	<?php if (isset($_SESSION['user']['items'])) { ?>
    					<?php foreach ($_SESSION['user']['items'] as $key=>$item) { ?>
                            <?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['id']} AND status=1"); ?>
                            <?php if ($db->affected_rows > 0) { ?>
                            	<?php 
    								if ($item['size'] > 0) { 
    									$itemSize  = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['size']}");
    									// $itemValue = $itemSize['value'];
                                        $itemValue = ($lang == 'cn' ? ($itemSize['value_cn']==""?$itemSize['value']:$itemSize['value_cn']) : $itemSize['value']);
    									$itemPrice = $itemSize['price'];
    								} else {
    									$itemValue = $ir['value'];
    									$itemPrice = $ir['price'];
    								}
    							?>
                                <div class="full-order-price-row">
                                    <span class="first-element"><?php echo $item['quantity']; ?> x n.<?php __($ir['item_number']); ?>: <?php __($ir['name']); ?> <?php echo $itemValue; ?></span>
                                    <span class="second-element"><?php echo _priceSymbol; ?></span>
                                    <span class="third-element"><?php echo number_format($itemPrice*$item['quantity'],2); ?></span>
                                    <a href="index.php?page=order-details&remove_item=<?php echo $item['id']; ?>&size=<?php echo $item['size']; ?>" class="delete-button"></a>
                                </div>
                                <?php $total_price += number_format($itemPrice*$item['quantity'],2); ?>
    						<?php } // $db->affected_rows > 0 ?>
                        <?php } // endforeach ?>

                        <?php
                            if ((isset($_SESSION['user']['delivery_fee'])&&($_SESSION['user']['delivery_fee']>0))) {
                                echo '<div class="full-order-price-row">
                                    <span class="first-element">'.($xml->$lang->dellfee==""?$xml->en->dellfee:$xml->$lang->dellfee).'</span>
                                    <span class="second-element">RMB</span>
                                    <span class="third-element">'.number_format($_SESSION['user']['delivery_fee'],2).'</span>
                                </div>';
                                $total_price += $_SESSION['user']['delivery_fee'];
                            }
                        ?>

                    <?php } // end isset ?>
                        
                    <div class="full-order-price-row final">
                    	<span class="first-element"><?php echo ($xml->$lang->total==""?$xml->en->total:$xml->$lang->total); ?></span>
                    	<span class="second-element"><?php echo _priceSymbol; ?></span>
                    	<span class="third-element"><?php echo number_format($total_price,2); ?></span>
                    </div>
                
        	   </div>

                <form class="form-horizontal">
                    
                    <div class="form-group" style="margin-top:1em;">
                        <div class="col-md-offset-3">
                            <a href="index.php?page=menu&restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo$_SESSION['user']['restaurant']['id']; ?>" class="btn btn-info"><?php echo ($xml->$lang->add==""?$xml->en->add:$xml->$lang->add); ?></a>
                        </div>
                    </div>

                    <div class="form-group form-horizontal">
                        <label for="itinerary" class="control-label col-sm-4"><?php echo ($xml->$lang->note==""?$xml->en->note:$xml->$lang->note); ?></label>
                        <div class="col-sm-8">
                            <textarea name="notesDummy" id="notesDummy" class="form-control" maxlength="160"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                        </div>
                    </div>
                </form>

                

            </div>

        </div>
    	
        
    	<div class="col-sm-6 myfonts">
            <div class="well">
            	<?php if (isset($_SESSION['user']['id'])) { ?>
                	<?php $uData = $db->query_first("SELECT * FROM users WHERE id={$_SESSION['user']['id']}"); ?>
                	
                        
                        <div id="lastminsignup">

                            <h2><?php echo ($xml->$lang->recip==""?$xml->en->recip:$xml->$lang->recip); ?></h2>

                            <form action="index.php?page=order-summary" method="post" class="form form-horizontal">

                                <p><?php echo ($xml->$lang->check==""?$xml->en->check:$xml->$lang->check); ?></p>


                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4"><?php echo ($xml->$lang->name==""?$xml->en->name:$xml->$lang->name); ?>:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="name" value="<?php echo $uData['name']; ?>">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4"><?php echo ($xml->$lang->phone==""?$xml->en->phone:$xml->$lang->phone); ?>:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="phone" value="<?php echo $uData['phone']; ?>">
                                    </div>
                                </div>                                


                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4"><?php echo ($xml->$lang->area==""?$xml->en->area:$xml->$lang->area); ?>:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="area" name="area">
                                            <option hidden="">Select</option>
                                            <?php $areas = $db->query("SELECT * FROM areas WHERE $status=1 ORDER BY title ASC"); ?>
                                            <?php while ($r=$db->fetch_array($areas)) { ?>
                                                <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['area_id'] ? 'selected':NULL; ?>>
                                                    <?php echo ($lang=='cn'?($r['title_cn']==""?$r['title']:$r['title_cn']):$r['title']); ?>
                                                </option>
                                            <?php } // while $areas loop ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4"><?php echo ($xml->$lang->build==""?$xml->en->build:$xml->$lang->build); ?>:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="building" name="building">
                                            <option hidden="hidden">Select</option>
                                            <?php $cuisines = $db->query("SELECT * FROM buildings WHERE $status=1 ORDER BY title ASC"); ?>
                                            <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                                <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['building_id'] ? 'selected':NULL; ?>>
                                                    <?php echo ($lang=='cn'?($r['title_cn']==""?$r['title']:$r['title_cn']):$r['title']); ?>
                                                </option>
                                            <?php } // while $areas loop ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4"><?php echo ($xml->$lang->blok==""?$xml->en->blok:$xml->$lang->blok); ?>:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="apartment" value="<?php echo $uData['apartment']; ?>">
                                    </div>
                                </div>                                                           

                                <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                                <input type="hidden" name="price" id="price" value="<?php echo $total_price; ?>" />                

                                <div class="form-group" style="margin-top:1em;">
                                    <div class="col-md-offset-4 col-md-1">
                                        <button type="submit" class="btn btn-yellow"><?php echo ($xml->$lang->confi==""?$xml->en->confi:$xml->$lang->confi); ?></button>
                                    </div>
                                </div>


                                
                                <?php if (isset($_SESSION['msg'])) { ?>
                                    <div class="clearfix"></div>
                                    <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>" style="text-align:center;">
                                        <br /><?php echo $_SESSION['msg']; ?>
                                    </div>
                                <?php } // isset $_SESSION['msg'] ?>
                        	</form>
                        </div>
                    
                <?php } else { // if (!isset($_SESSION['user']['id']) ?>

                    <div>
                        
                        <form action="index.php?page=login&redirect=<?php echo urldecode('index.php?page=order-details'); ?>" method="post" class="form-horizontal">
                            <h2>Existing Member</h2>

                            <div class="form-group form-horizontal">
                                <label class="control-label col-sm-4">E-mail Address:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="email" name="email">
                                </div>
                            </div>
                            <div class="form-group form-horizontal">
                                <label class="control-label col-sm-4">Password:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="password" name="password" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <a href="index.php?page=forgot-password" class="forgot-password">Forgotten your password?</a>
                                </div>
                            </div>

                            <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                            
                            <div class="form-group" style="margin-top:1em;">
                                <div class="col-md-offset-4 col-md-1">
                                    <button type="submit" class="btn btn-yellow">Login</button>
                                </div>
                            </div>

                        </form>

                        <div class="your-info-registration">
                            
                            <form action="index.php?page=login?signup=true&redirect=<?php echo urldecode('index.php?page=order-details'); ?>" method="post" class="form-horizontal"> 
                            
                                <h2>New Member</h2>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Name:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="name">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Phone:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="phone">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Your Area:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="area" name="area">
                                            <option hidden="hidden">Select</option>
                                            <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                            <?php while ($r=$db->fetch_array($areas)) { ?>
                                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                            <?php } // while $areas loop ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Your Building:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="building" name="building">
                                            <option hidden="hidden">Select</option>
                                            <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                            <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                            <?php } // while $areas loop ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Block / Apt:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="apartment">
                                    </div>
                                </div>



                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Email address:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="email" name="email1">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Re-enter email address:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="email" name="email2">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Password:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="password" name="password1">
                                    </div>
                                </div>

                                <div class="form-group form-horizontal">
                                    <label class="control-label col-sm-4">Re-enter password:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="password" name="password2">
                                    </div>
                                </div>


                                <div class="col-xs-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="newsletter" checked="checked" value="1">I would like to sign up to the SHENZHEN EAT newsletter (via email and mobile) for the chance to win free takeaway for a year.</label>
                                    </div>
                                </div>
                            
                                <div class="col-xs-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="terms" value="1">I accept the <a href="#">terms and conditions</a>, <a href="#">privacy policy</a> &amp; <a href="#">cookies policy</a></label>
                                    </div>
                                </div>

                                <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                                
                                <div class="form-group" style="margin-top:1em;">
                                    <div class="col-md-offset-1 col-md-1">
                                        <button type="submit" class="btn btn-yellow">Next</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                        
                    </div>
                <?php } // if (!isset($_SESSION['user']['id']) ?>
            </div>
        </div>
    	
        
    </div>
	<!-- end of main content -->
</div>