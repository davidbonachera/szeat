<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php
$id = addslashes($_GET['id']);
$mmid = addslashes($_GET['m_id']); //print_r( $id); print_r( $_GET['m_id']); die; 
?>
<?php
unset($_SESSION["menu"]);
if (empty($_SESSION["menu"])) {
    $_SESSION["menu"] = $mmid;
}




$pData = array(
    array(
        'link' => 'dashboard.php',
        'title' => 'Dashboard'
    ),
    array(
        'link' => 'restaurants.php',
        'title' => 'Restaurants'
    ),
    array(
        'link' => 'menus.php?id=' . $id,
        'title' => ' Menu '
    ),
    array(
        'link' => 'menu_layers_show.php?id=' . $id . '&m_id=' . $mmid,
        'title' => ' Layers '
    )
);
?>
<?php
if (!checkFeild($id)) {
    $_SESSION['msg'] = "Restaurant Not Found.";
    $_SESSION['error'] = false;
    header("Location: restaurants.php");
    exit();
}

if (!isset($_SESSION['item']))
    $_SESSION['item'] = "";

if ($_POST) {
    if ($_POST['form'] == 'selectbox') {
        //echo "<pre>"; print_r($_POST); die;

        $request_column = $_POST['row_id'];
        $request_column_order = $_POST['new_selected'];

        $response_cloumn;
        $response_column_order;
        $old_order;

        $request_col = $db->query("SELECT  * FROM menu_item_layers WHERE  id='$request_column'  ");

        while ($rest = $db->fetch_array($request_col)) {

            $old_order = $rest['order_by'];
        }





        $resp_column = $db->query("SELECT  * FROM menu_item_layers WHERE restaurant_id='$id' AND menu_item_id='$mmid' AND order_by='$request_column_order'  ");

        while ($resp = $db->fetch_array($resp_column)) {
            $response_column_order = $resp['order_by'];
            $response_cloumn = $resp['id'];
        }

        $request_data['order_by'] = $request_column_order;
        // echo "<pre>"; print_r($request_column);
        //echo "<pre>"; print_r($request_data);



        if ($db->query_update("menu_item_layers", $request_data, "id='$request_column'")) {
            //$response_data['order_by'] = $old_order;
            // echo "<pre>"; print_r($response_cloumn);
            //echo "<pre>"; print_r($response_data); die;
            $sql = "UPDATE `db_szeat`.`menu_item_layers` SET `order_by` = '" . $old_order . "' WHERE `menu_item_layers`.`id` = " . $response_cloumn . " ";
            $result = mysql_query($sql);
            if ($result) {
                //print_r("alldone");die;
            }
        }
    }

    if ($_POST['form'] == 'layers') {

        //print_r($_POST); die;
        if (checkFeild(checkFeild($_POST['name']) && checkFeild($_POST['maximum_selected']))) {

            //print_r($_POST); die;
            $last_order;

            $orderby = $db->query("SELECT  max(order_by) as orderby FROM menu_item_layers WHERE restaurant_id='$id' AND menu_item_id='$mmid' ");
            if ($db->affected_rows > 0) {
                while ($ar_order = $db->fetch_array($orderby)) {
                    if ($ar_order != 0) {
                        // die("if");
                        $last_order = $ar_order['orderby'];
                        $last_order = $last_order + 1;
                    } else {
                        //  die("else");
                        //$last_order=1; 
                    }
                }
            }// print_r($last_order);die;
            $data['order_by'] = $last_order;
            $data['restaurant_id'] = $id;
            $data['menu_item_id'] = $mmid;
            $data['maximum_selected'] = $_POST['maximum_selected'];
            $data['name'] = ucwords(strtolower($_POST['name']));
            $data['name_cn'] = ucwords(strtolower($_POST['name_cn']));
            $data['description'] = $_POST['description'];
            $data['description_cn'] = $_POST['description_cn'];
            $data["status"] = 1;
            $data["status_cn"] = 1;
            $data["date"] = _nowdt;
            //	echo "<pre>";print_r($data ); die;

            if ($db->query_insert("menu_item_layers", $data)) {
                $_SESSION['error'] = false;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = "Menu Item Layer successfully added";
                header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
                exit();
            } else {
                $_SESSION['error'] = true;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = "Menu Item Layer couldn't be added";
            }
        } else {
            $_SESSION['error'] = true;
            $_SESSION['item'] = "item";
            $_SESSION['msg'] = "All * marked fields are quired.";
        }
    }
}

/* <a class="btn btn-info" href="layers_list_show.php?id=<?php echo $id; ?>
  &menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>" title="Layer Attributes"> */


if (isset($_GET['mi_lid']) && isset($_GET['menuid'])) {
    $dhID = addslashes($_GET['mi_lid']);
    $mmid = addslashes($_GET['menuid']);
    //$menuid=addslashes($_GET['menuid']);
    if (isset($_GET['delete'])) {
        $orderoflyr;

        // fatch order of deleted layer
        $query1 = $db->query("SELECT * FROM menu_item_layers WHERE id='$dhID' LIMIT 1");
        if ($db->affected_rows > 0) {
            while ($r1 = $db->fetch_array($query1)) {
                $orderoflyr = $r1['order_by'];
            }
        }







        if ($db->query("DELETE FROM menu_item_layers WHERE id='$dhID' LIMIT 1")) {

            //echo "DELETE FROM layer_lists WHERE layer_id='$dhID'"; die;
            if ($db->query("DELETE FROM layer_lists WHERE layer_id='$dhID' ")) {
                //print_r("deleted"); die;
            }
            //die("here");
            // update order of all layers next to deleted layers
            $query3 = $db->query("SELECT * FROM menu_item_layers WHERE menu_item_id={$mmid} AND order_by>{$orderoflyr} ORDER BY order_by ASC ");
            if ($db->affected_rows > 0) {
                while ($r3 = $db->fetch_array($query3)) {
                    $datadel['order_by'] = $r3['order_by'] - 1;
                    $nextid = $r3['id'];

                    $data['status'] = $_GET['status'];

                    if ($db->query_update("menu_item_layers", $datadel, "id='$nextid'")) {
                        
                    }
                }
            }



            $_SESSION['msg'] = "Menu Item Layer successfully deleted.";
            $_SESSION['error'] = false;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item Layer can't be deleted.";
            $_SESSION['error'] = true;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        }
    } elseif (isset($_GET['status'])) {

        $data['status'] = $_GET['status'];

        if ($db->query_update("menu_item_layers", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Menu Item Layer status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item Layer status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        }
    } elseif (isset($_GET['status_cn'])) {

        $data['status_cn'] = $_GET['status_cn'];

        if ($db->query_update("menu_item_layers", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Menu Item Layer status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item Layer status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menu_layers_show.php?id=$id&m_id=$mmid");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Menu Item Layers</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link id="bootstrap-style" href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link id="base-style" href="css/style.css" rel="stylesheet">
        <link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
        <!--[if lt IE 7 ]>
                <link id="ie-style" href="css/style-ie.css" rel="stylesheet">
        <![endif]-->
        <!--[if IE 8 ]>
                <link id="ie-style" href="css/style-ie.css" rel="stylesheet">
        <![endif]-->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="img/favicon.ico">
    </head>
    <body>
        <?php include("include/header.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <?php include("include/sidebar.php"); ?>
                <?php include("include/no-javascript-error.php"); ?>
                <div id="content" class="span10">
                    <!-- start: Content -->
                    <?php include("include/breadcrumbs.php"); ?>

                    <?php if (isset($_SESSION['msg'])) { ?>
                        <div class="box-content alerts">
                            <div class="alert alert-<?php echo $_SESSION['error'] == true ? 'error' : 'success'; ?>">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $_SESSION['msg']; ?>
                            </div>
                        </div><!--alerts-->
                    <?php } // if isset($_SESSION['msg'])    ?>
                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Menu Item Layer</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-<?php echo $_SESSION['item'] == "item" ? "up" : "down"; ?>"></i></a>
                                </div>
                            </div>
                            <div class="box-content" style="display:<?php echo $_SESSION['item'] == "item" ? "block" : "none"; ?>;">
                                <form class="form-horizontal" method="post">
                                    <input type="hidden" name="form" id="form" value="layers" />
                                    <fieldset>
                                        <!--   <div class="control-group">
                                          <label class="control-label" for="menu_cat_id">* Category</label>
                                          <div class="controls">
                                             <select id="menu_cat_id" name="menu_cat_id" data-rel="chosen">
                                        <?php $areas = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title ASC"); ?>
                                           
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($ar = $db->fetch_array($areas)) { ?>
                                                                                           <option value="<?php echo $ar['id']; ?>"><?php echo $ar['title']; ?></option>
                                            <?php } // endwhile $areas  ?>
                                        <?php } // $db->affected_rows    ?>
                                             </select>
                                           </div>
                                         </div>-->

                                        <div class="control-group">
                                            <label class="control-label" for="name">* English Name</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="name" name="name" type="text" value="" required="required">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="name_cn">Chinese Name</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="name_cn" name="name_cn" type="text" value="">
                                            </div>
                                        </div>                                      
                                        <div class="control-group">
                                            <label class="control-label" for="description">Description</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge focused" id="description" name="description" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="description_cn">Chinese Description</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge focused" id="description_cn" name="description_cn" rows="3"></textarea>
                                            </div>
                                        </div>   
                                        <div class="control-group">
                                            <label class="control-label" for="maximum_selected">Limit</label>
                                            <div class="controls">
                                                <input type="input" class="input-xlarge focused" id="maximum_selected" name="maximum_selected" type="text" required="required">
                                            </div>
                                        </div>


                                        <!--
                                        <div class="control-group">
                                          <label class="control-label" for="value">* Quantity</label>
                                          <div class="controls">
                                            <input class="input-xlarge focused" id="value" name="value" type="text" value="">
                                          </div>
                                        </div>
                                       
                                        <div class="control-group">
                                          <label class="control-label" for="price">* Price</label>
                                          <div class="controls">
                                            <div class="input-prepend">
                                              <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="">
                                            </div>
                                          </div>
                                        </div> -->
                                        <div class="form-actions">
                                            <button id="submit" name="submit" type="submit" class="btn btn-primary">Save changes</button>
                                            <button class="btn" id="cancelButton">Cancel</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div><!--/span-->
                    </div><!--/row-->
                    <div class="row-fluid sortable">		
                        <div class="span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-leaf"></i><span class="break"></span>Menu Items Layer</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
                                </div>
                            </div>

                            <div class="box-content">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>English Name</th>
                                            <th>Chinese Name</th>

                                            <th>Description</th>
                                            <th>Description_cn</th>

                                            <th>English Status</th>
                                            <th>Chinese Status</th>
                                            <th>Order</th>
                                            <th>Max. Limit</th>


                                            <th>Actions</th>
                                        </tr>
                                    </thead>   
                                    <tbody>
                                        <?php $query = $db->query("SELECT * FROM menu_item_layers WHERE restaurant_id='$id' AND menu_item_id='$mmid' "); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($r = $db->fetch_array($query)) { //print_r($r); die;   ?>
                                                <tr>
                                                    <td><?php echo $r['id']; ?></td>
                                                    <td><?php echo $r['name']; ?></td>
                                                    <td><?php echo $r['name_cn']; ?></td>
                                                <!--    <td><?php echo getData('menu_categories', 'title', $r['menu_cat_id']); ?></td>-->

                                                    <td class="smallFont"><?php echo $r['description']; ?></td>
                                                    <td class="smallFont"><?php echo $r['description_cn']; ?></td>

                                                    <td class="center">
                                                        <span class="label <?php echo $r['status'] == 1 ? 'label-success' : NULL; ?>">
                                                            <?php echo $r['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                                        </span>
                                                    </td>
                                                    <td class="center">
                                                        <span class="label <?php echo $r['status_cn'] == 1 ? 'label-success' : NULL; ?>">
                                                            <?php echo $r['status_cn'] == 1 ? 'Active' : 'Inactive'; ?>
                                                        </span>
                                                    </td>  

                                                    <?php $order_list = $db->query("SELECT  order_by as orderby FROM menu_item_layers WHERE restaurant_id='$id' AND menu_item_id='$mmid'"); ?>

                                                    <td>
                                                        <form action='menu_layers_show.php?id=<?php echo $id ?>&m_id=<?php echo $mmid ?>' method='post' id="orderForm<?php echo $r['id'] ?>" dir="#orderForm<?php echo $r['id'] ?>" >
                                                            <input type="hidden" name="form" id="form" value="selectbox" />
                                                            <input type="hidden" name="row_id"  value="<?php echo $r['id'] ?>" />
                                                            <select class="selectClass" name = 'new_selected' style = 'position: relative; width:50px;'  >
                                                                <?php
                                                                while ($content = mysql_fetch_array($order_list)) {

                                                                    if ($content['orderby'] == $r['order_by'])
                                                                        echo "<option value='" . $content['orderby'] . "' selected>" . $content['orderby'] . "</option>";
                                                                    else
                                                                        echo "<option value='" . $content['orderby'] . "'>" . $content['orderby'] . "</option>";
                                                                }
                                                                ?>
                                                            </select></form></td>
                                                            
                                                     <td class="center">
                                                        <span class="label">
                                                            <?php echo $r['maximum_selected']; ?>
                                                        </span>
                                                    </td>  

                                                    <td class="center">
                                                        <a class="btn btn-info" href="menu-item-layers-edit.php?id=<?php echo $id; ?>&menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>" title="Layer Attributes">
                                                            <i class="icon-edit icon-white"></i>  
                                                        </a>
                                                        <a class="btn btn-inverse" href="?id=<?php echo $id; ?>&menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>&status=<?php echo $r['status'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>
                                                        <a class="btn btn-warning" href="?id=<?php echo $id; ?>&menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status_cn'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>                                                    


                                                        <a class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $r['id']; ?>" title="Delete">
                                                            <i class="icon-trash icon-white"></i>
                                                        </a>
                                                        <div class="modal fade" id="deleteModal<?php echo $r['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="col">

                                                                            <p>Are you sure?</p>
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                            <a class="btn btn-danger" href="?id=<?php echo $id; ?>&menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                                                <i class="icon-trash icon-white"></i> 
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>








                                                        <a class="btn btn-info" href="layers_list_show.php?id=<?php echo $id; ?>&menuid=<?php echo $mmid ?>&mi_lid=<?php echo $r["id"]; ?>" title="Layer Attributes">
                                                            <i class="icon-th-list icon-white"></i>  
                                                        </a>


                                                    </td>
                                                </tr>
                                            <?php } // endwhile $query loop  ?>
                                        <?php } // $db->affected_rows    ?>
                                    </tbody>
                                </table>            
                            </div>
                        </div><!--/span-->

                    </div><!--/row-->

                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
            <div class="clearfix">&nbsp;</div>
            <?php include("include/footer.php"); ?>		
        </div><!--/.fluid-container-->
        <?php include("include/footer-inc.php"); ?>
        <?php unset($_SESSION['msg'], $_SESSION['error'], $_SESSION['item']); ?>
    </body>
    <script>
        $('.selectClass').change(function () {

            var formid = $(this).parent().attr("dir");
            //alert(formid);
            $(formid).submit();
        });
    </script>
</html>
