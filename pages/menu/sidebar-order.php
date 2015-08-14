<style>
    .main_cl_items {
        float: left;
        font-size: 12px;
        margin-bottom: 15px;
        margin-left: 5px;
        padding: 0;
        width: 217px;
    }
    .main_cl_items p{border-bottom:solid 1px #666}
    .item_in_all{width:40px; float:left; color:#666; font-size: 12px;}
    .item_in_all10{width:114px; float:left; color:#666; font-size: 12px;}
    .mail_orde_righ_al{ margin:0; width:244px}
    .w_full{float:left; width:100%}
    .right_tab{position: absolute; right: 13%;}
    @media (min-width: 1068px) and (max-width: 1268px) {
        .right_tab {
            position: absolute;
            right: 5%;
        }
    }
    @media (min-width: 768px) and (max-width: 1068px) {
        .right_tab {
            position: absolute;
            right: 0%;
        }
        .mail_orde_righ_al {
            margin: 0;
            width: 204px;
        }

    }

    @media (min-width: 200px) and (max-width: 768px) {
        .right_tab {
            left: 16px;
            margin: 12px 0;
            position: relative;
            right: 0;
        }
        .item_in_all{width:33.3333%; float:left; color:#666; font-size: 12px;}
        .item_in_all10{width:33.3333%; float:left; color:#666; font-size: 12px;}
        .mail_orde_righ_al {
            background: hsl(0, 0%, 100%) none repeat scroll 0 0;
            margin: 0;
            position:relative;
            top: 0;
            width: 95%;
            z-index: 999;
        }
        .main_cl_items {
            float: left;
            font-size: 12px;
            margin-bottom: 15px;
            margin-left: 9px;
            padding: 0;
            width: 100%;
        }

    }
</style>

<div class="right_tab">
    <div id="" class="mail_orde_righ_al">
        <?php if (isset($_SESSION['error']) && $_SESSION['error'] == true) { ?>
            <div class="error"><?php echo $_SESSION['msg']; ?></div>
        <?php } ?>

        <div class="block-page">


            <h2><?php echo ($xml->$lang->urorder == "" ? $xml->en->urorder : $xml->$lang->urorder); ?></h2>
            <ul id="sidebarorder">
                <?php
                if (isset($_SESSION['user']['items'])) { //echo "<pre>"; print_r($_SESSION['user']['items']); 
                    if ($res['delivery_fee'] > 0) {
                        $totalItemPrice = $res['delivery_fee'];
                        $_SESSION['user']['delivery_fee'] = $res['delivery_fee'];
                    } else {
                        $totalItemPrice = 0;
                        unset($_SESSION['user']['delivery_fee']);
                    }
                    //echo "<pre>"; print_r($_SESSION['user']['items']); die;
                    foreach ($_SESSION['user']['items'] as $key => $item) { // echo "<pre>"; print_r($item['layers']['id']); die; 
                        ?>


                        <?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['id']} AND $status=1"); ?>
                        <?php if ($db->affected_rows > 0) { ?>
                            <div class="item_single" >
                                <?php
                                if ($item['size'] > 0) {
                                    $itemSize = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['size']}");
                                    // $itemValue = $itemSize['value'];
                                    $itemValue = ($lang == 'cn' ? ($itemSize['value_cn'] == "" ? $itemSize['value'] : $itemSize['value_cn']) : $itemSize['value']);
                                    $itemPrice = $itemSize['price'];
                                } else {
                                    $itemValue = $ir['value'];
                                    $itemPrice = $ir['price'];
                                }
                                ?>


                                <li>
                                    <p><?php echo ($lang == 'cn' ? ($ir['name_cn'] == "" ? $ir['name'] : $ir['name_cn']) : $ir['name']); ?> <?php if($itemValue != 0) echo $itemValue; ?></p>
                                    <div class="col-xs-4"><?php echo $item['quantity']; ?>x</div>
                                    <div class="col-xs-4"><?php echo _priceSymbol; ?></div>
                                    <?php $itemPriceCalculated = number_format($itemPrice * $item['quantity'], 2); ?>
                                    <?php $totalItemPrice += $itemPriceCalculated; ?>
                                    <div class="col-xs-4" style="padding:0;"><i><?php echo $itemPriceCalculated; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>&remove_item=<?php echo $item['id']; ?>&size=<?php echo $item['size']; ?>"></i><img src="img/order-delete.png" alt=""  /></a></div>
                                    <hr>

                                    <div class="main_cl_items">

                                        <?php
                                        foreach ($item['layers'] as $lay_id) {
                                            //echo "<pre>";print_r($lay_id);die;
                                            //print_r($llaayyeerr);die;
                                            $layers = $db->query_first("SELECT * FROM menu_item_layers WHERE id={$lay_id['id']} AND $status=1");
                                            $item_layer_name = $layers['name'];
                                            //print_r($item_layer_name);die;
                                            ?>  
                                            <p><?php echo $item_layer_name; ?></p> 

                                            <?php
                                            //$attributes = $db->query_first("SELECT * FROM layer_lists WHERE id={$item['layers']['attributes']} AND $status=1"); 

                                            foreach ($lay_id['attributes'] as $attribid => $attribqty) {
                                                //	print_r($attrib);die;
                                                $attributes = $db->query_first("SELECT * FROM layer_lists WHERE id={$attribid} AND $status=1");
                                                $attrib_name = $attributes['name'];
                                                $attrib_price = $attributes['price'];
                                                $newprice = $attrib_price * $attribqty;

                                                //for total pricing of attributes
                                                $totalItemPrice = number_format($newprice, 2) + $totalItemPrice;
                                                ?> 

                                                <div class="w_full">
                                                    <div class="item_in_all10"><?php echo $attribqty; ?>x <?php echo $attrib_name; ?></div>
                                                    <div class="item_in_all"><?php echo _priceSymbol; ?></div>
                                                    <div class="item_in_all"><?php echo number_format($newprice, 2); ?></div>
                                                </div>

                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>




                                </li>

                            </div>
                        <?php } // $db->affected_rows > 0  ?>



                        <?php
                    } // endforeach 



                    if ($res['delivery_fee'] > 0) {
                        echo '

                        <li>
                            <p>Delivery fee</p>
                            <div class="col-xs-4">1x</div>
                            <div class="col-xs-4">RMB</div>
                            <div class="col-xs-4" style="padding:0;"><i>' . number_format($res['delivery_fee'], 2) . '</i></div>
                        </li>

                        ';
                    }
                    ?>
                    <li>
                        <p><?php echo ($xml->$lang->subtot == "" ? $xml->en->subtot : $xml->$lang->subtot); ?>:</p>
                        <div class="col-xs-4"><?php echo _priceSymbol; ?></div>
                        <i style="font-weight:normal;float:right;"><?php echo number_format($totalItemPrice, 2); ?></i>
                    </li>
                <?php } // end isset ?>


            </ul>

            <?php
// stop submit if less than resto's min order
            $spendEnough = false;
            if (($totalItemPrice - $res['delivery_fee']) >= $res['minimum_order']) {
                $spendEnough = true;
            } else {
                echo '<p class="redwarning">' . ($xml->$lang->minorderreach == "" ? $xml->en->minorderreach : $xml->$lang->minorderreach) . '</p>';
            }
            ?>

            <div class="order-button">
                <div class="view-menu"><a class="btn btn-yellow" href="<?php echo (($deliveryAvailable == false) || ($spendEnough != true)) ? "" : "index.php?page=order-details"; ?>"><?php echo ($xml->$lang->ordernow == "" ? $xml->en->ordernow : $xml->$lang->ordernow); ?></a></div>
            </div>
        </div>
    </div>
</div>