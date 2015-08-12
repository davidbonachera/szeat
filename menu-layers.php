<?php
$one_time_flag = 0;
include('class/config.inc.php');
$lang = $_GET['lang'];
$item_name;
$sqlfirst = "SELECT * FROM menu_items WHERE id='" . $_GET['add_item'] . " '  ";
$resultfirst = mysql_query($sqlfirst);
while ($rowfirst = mysql_fetch_assoc($resultfirst)) {
    $item_name = $rowfirst['name'];
}

$sql = "SELECT * FROM menu_item_layers WHERE menu_item_id='" . $_GET['add_item'] . " ' ORDER BY order_by ASC ";
$result = mysql_query($sql);
//echo $result; die;
if (mysql_num_rows($result) > 0) {
    $desired_id = array();
    while ($row1 = mysql_fetch_assoc($result)) {
        //echo "<pre>";
        //print_r($row);
        $sql21 = "SELECT * FROM layer_lists WHERE layer_id='" . $row1['id'] . " ' ";
        $result21 = mysql_query($sql21);
        if (mysql_num_rows($result21) > 0) {

            // output data of each row

            while ($column1 = mysql_fetch_assoc($result21)) {
                $sizeArr = explode(',', $column1['size']);
                //echo "<pre>";print_r($sizeArr);
                if (in_array($_GET['size'], $sizeArr)) {
                    $desired_id[] = $column1['layer_id'];
                }
            }
        }
    }
    $in = "(" . implode(',', $desired_id) . ")";
    //print_r($desired_id);
    //die;
    $layerid = 1;
    // output data of each row
    if (!empty($desired_id)) {
        $sql = "SELECT * FROM menu_item_layers WHERE menu_item_id='" . $_GET['add_item'] . " ' and `id` IN $in ORDER BY order_by ASC ";
    } else {
        $sql = "SELECT * FROM menu_item_layers WHERE menu_item_id='" . $_GET['add_item'] . " ' ORDER BY order_by ASC ";
    }
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
        ?>						

        <?php if ($one_time_flag == 0) { ?>

            <div class="fatchdata " id="layer<?php echo $layerid; ?>"  b="<?php echo $row['order_by']; ?>"  style="display:block;">

                <?php
                $one_time_flag = 1;
            } else {
                ?>

                <div class="fatchdata " id="layer<?php echo $layerid; ?>" b="<?php echo $row['order_by']; ?>" dir="<?php echo $row['id']; ?>" style="display:none;">

                    <?php
                    $one_time_flag = 1;
                }
                ?>
                <!-- get url from first ajax and send  to page for redirecting following url on next ajax -->
                <input type="hidden" id="max_selected<?php echo $row['id']; ?>" value="0" max_value = "<?php echo $row['maximum_selected']; ?>" dir = '0'>

                <div class="h_top_heding"><h5> <?php echo $item_name; ?></h5></div>
                <div class="body_in" style="">
                    <h5><?php if ($lang == 'cn' && !empty($row['name_cn'])) {
            echo $row['name_cn'];
        } else {
            echo $row['name'];
        } ?>  </h5>

                    <p><?php if ($lang == 'cn' && !empty($row['description_cn'])) {
            echo $row['description_cn'];
        } else {
            echo $row['description'];
        } ?> </p>

                    <h4 class="button skip" id="yes<?php echo $row['id']; ?>" style="display:none;width:70%;float:left;" dir="<?php echo $row['id']; ?>"  >Yes please</h4>


                    <h4 class="button2 skip" style="display:block;width:70%;float:left;" id="skip<?php echo $row['id']; ?>" dir="<?php echo $row['id']; ?>"  ><?php if ($lang == 'cn') {
            echo "跳躍";
        } else {
            echo "Skip";
        } ?></h4>
                    <h4 class="button2 back_button" style="width:28%;float:right;background-color: #900 !important;color:#FFF !important;"id="back<?php echo $row['id']; ?>" dir="<?php echo $row['id']; ?>"><?php if ($lang == 'cn') {
                                    echo "
背部";
                                } else {
                                    echo "Back";
                                } ?></h4>
                    <div class="addExtrasTotal">
                        <span><b>Total<b></span>
                                    <span style="float:right;"    ><b  id="totalPrice<?php echo $row['id']; ?>">0</b></span>
                                    <input type="hidden" id="attributeid<?php echo $row['id']; ?>" value="" >

                                    </div>
                                    <!--<a class="button" href="#">Yes pleafghfghse</a>-->

                                    <ul>
        <?php
        $sql2 = "SELECT * FROM layer_lists WHERE layer_id='" . $row['id'] . " ' ";
        $result2 = mysql_query($sql2);

        if (mysql_num_rows($result2) > 0) {

            // output data of each row

            while ($column = mysql_fetch_assoc($result2)) {
                $sizeArr = explode(',', $column['size']);
                //echo "<pre>";print_r($sizeArr);
                if (in_array($_GET['size'], $sizeArr) || ($_GET['size'] == 0)) {
                    ?>
                                                    <input type="hidden" id="attr_id-<?php echo $column['id']; ?>" value="0" max="<?php echo $column['maximum_selected']; ?>">
                                                    <li class="li_sty_fist plus_item"style="display:block">
                                                        <div class="addItem">
                                                            <div class="item"><?php if ($lang == 'cn' && !empty($column['name_cn'])) {
                                    echo $column['name_cn'];
                                } else {
                                    echo $column['name'];
                                } ?> </div>
                                                            <div class="price">RMB<?php echo " " . $column['price']; ?> </div>
                                                            <div class="right addbutton"  style="cursor: pointer;" q="<?php echo $row['id']; ?>" dir="<?php echo $column['id']; ?>" b="<?php echo $column['name']; ?>" s="<?php echo $column['price']; ?>" a="<?php echo $row['id']; ?>"><a class="button1 " href="JavaScript: void(0);">+</a></div></div></li>


                                                    <li class="li_sty_fist_last_misns minus_item" id="minus<?php echo $column['id']; ?>" style="display:none">
                                                        <div class="addItem">
                                                            <div class="item" id="attr_quantity<?php echo $column['id']; ?>" > 0</div>
                                                            <div class="price">RMB<?php echo " "; ?> <b id="single_total<?php echo $column['id']; ?>">0</b></div>
                                                            <div class="right removebutton"  style="cursor: pointer;" q="<?php echo $row['id']; ?>" dir="<?php echo $column['id']; ?>" b="<?php echo $column['name']; ?>" s="<?php echo $column['price']; ?>" ><a class="button_none  " href="JavaScript: void(0);"><img src="img/minus.png" /></a></div></div></li>

                                                <?php
                                            }
                                        }
                                    } else {
                                        //echo "sdfghjkl;";
                                    }
                                    ?>



                                    </ul>
                                    </div>
                                    </div>								

        <?php
        $layerid++;
    }
    ?>                   

                                <input type="hidden" id="total_layers" value="<?php echo $layerid; ?>" >

    <?php
} else {
    echo 1;
}
?>


