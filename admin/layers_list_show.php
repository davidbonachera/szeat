<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php
$id = addslashes($_GET['id']);

$menu_layer_id = addslashes($_GET['mi_lid']);
$menu_id = addslashes($_GET['menuid']);






// die($_GET);
//print_r( $id); print_r( $_GET['m_id']); die; 
?>
<?php
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
        'title' => 'Menu '
    ),
    array(
        'link' => 'menu_layers_show.php?id=' . $id . '&m_id=' . $_SESSION["menu"],
        'title' => ' Layers '
    ),
    array(
        'link' => 'layers_list_show.php?id=' . $id . '&menuid=' . $_SESSION["menu"] . '&mi_lid=' . $menu_layer_id,
        'title' => ' Attributes '
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
    if ($_POST['form'] == 'attributes') {

        //echo "<pre>";print_r($_POST );die();

        if (checkFeild($_POST['name']) && checkFeild($_POST['price'])) {




            //$menu_idd=$_POST['menu_id']
            //$data['layer_id'] 	= $menu_layer_id;
            $data['layer_id'] = $menu_layer_id;
            $data['price'] = $_POST['price'];
            $data['name'] = ucwords(strtolower($_POST['name']));
            $data['name_cn'] = ucwords(strtolower($_POST['name_cn']));
            $data['description'] = $_POST['description'];
            $data['description_cn'] = $_POST['description_cn'];
            $data['maximum_selected'] = $_POST['maximum_selected'];
            $data["status"] = 1;
            $data["status_cn"] = 1;
            $data["date"] = _nowdt;
            $size = '';
            if (isset($_POST['size']) && !empty($_POST['size'])) {
                $size = implode(',', $_POST['size']);
            }
            $data['size'] = $size;
            //echo "<pre>";print_r($data );die();

            if ($db->query_insert("layer_lists", $data)) {
                $_SESSION['error'] = false;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = "Layer List Attribute successfully added";
                //header("Location: layers_list_show.php?id=$id&mi_lid=$menu_layer_id");
                //exit();
            } else {
                $_SESSION['error'] = true;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = " Layer List Attribute couldn't be added";
            }
        } else {
            $_SESSION['error'] = true;
            $_SESSION['item'] = "item";
            $_SESSION['msg'] = "All * marked fields are quired.";
        }
    }
}

if (isset($_GET['layer']) && isset($_GET['attribute'])) {
    $layerID = addslashes($_GET['layer']);
    //die($layerID);
    $dhID = addslashes($_GET['attribute']);
    if (isset($_GET['delete'])) {
        if ($db->query("DELETE FROM layer_lists WHERE id='$dhID' LIMIT 1")) {
            $_SESSION['msg'] = "Layer List Attribute Item successfully deleted.";
            $_SESSION['error'] = false;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        } else {
            $_SESSION['msg'] = "Layer List Attribute can't be deleted.";
            $_SESSION['error'] = true;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        }
    } elseif (isset($_GET['status'])) {

        $data['status'] = $_GET['status'];

        if ($db->query_update("layer_lists", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Layer List Attribute status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        } else {
            $_SESSION['msg'] = "Layer List Attribute status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        }
    } elseif (isset($_GET['status_cn'])) {

        $data['status_cn'] = $_GET['status_cn'];

        if ($db->query_update("layer_lists", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Layer List Attribute status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        } else {
            $_SESSION['msg'] = "Layer List Attribute status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: layers_list_show.php?id=$id&mi_lid=$layerID");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Layers List</title>
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
                    <?php } // if isset($_SESSION['msg']) ?>
                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Menu Item Layers</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-<?php echo $_SESSION['item'] == "item" ? "up" : "down"; ?>"></i></a>
                                </div>
                            </div>
                            <div class="box-content" style="display:<?php echo $_SESSION['item'] == "item" ? "block" : "none"; ?>;">
                                <form class="form-horizontal" method="post">
                                    <input type="hidden" name="form" id="form" value="attributes" />
                                    <fieldset>
                                        <!--   <div class="control-group">
                                          <label class="control-label" for="menu_cat_id">* Category</label>
                                          <div class="controls">
                                             <select id="menu_cat_id" name="menu_cat_id" data-rel="chosen">
                                        <?php $areas = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title ASC"); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($ar = $db->fetch_array($areas)) { ?>
                                                                                                                                                                   <option value="<?php echo $ar['id']; ?>"><?php echo $ar['title']; ?></option>
                                            <?php } // endwhile $areas ?>
                                        <?php } // $db->affected_rows ?>
                                             </select>
                                           </div>
                                         </div>-->

                                        <div class="control-group">
                                            <label class="control-label" for="name">* English Name</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="name" name="name" type="text" value="">
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
                                        <?php $sizes = $db->query("SELECT * FROM menu_item_sizes WHERE menu_item_id='$menu_id' ORDER BY value ASC"); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <div class="control-group">
                                                <label class="control-label" for="description_cn">Size</label>
                                                <div class="controls">
                                                    <select class="input-xlarge focused" multiple="true" name="size[]">

                                                        <?php while ($size = $db->fetch_array($sizes)) { ?>
                                                            <option value="<?php echo $size['id']; ?>" selected="selected"><?php echo $size['value']; ?></option>
                                                        <?php } // endwhile $areas  ?>

                                                    </select>
                                                </div>
                                            </div>
                                        <?php } // $db->affected_rows ?>
                                        <div class="control-group">
                                            <label class="control-label" for="maximum_selected">Limit</label>
                                            <div class="controls">
                                                <input type="input" class="input-xlarge focused" id="maximum_selected" name="maximum_selected" type="text" required="required">
                                            </div>
                                        </div>

                                        <!-- <div class="control-group">
<label class="control-label" for="price">Attribute Price</label>
<div class="controls">
  <input class="input-xlarge focused" id="price" name="price" type="text" value="">
</div>
</div>-->

                                        <div class="control-group">
                                            <label class="control-label" for="price">* Price</label>
                                            <div class="controls">
                                                <div class="input-prepend">
                                                    <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="<?php echo $query['price']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <input id="price" name="layer_id" type="hidden" value="<?php echo $menu_layer_id; ?>" >
                                        <input id="abcd" name="menu_id" type="hidden" value="<?php echo $menu_id; ?>" >

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
                                <h2><i class="icon-leaf"></i><span class="break"></span>Menu Layers Attributes</h2>
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
                                            <th>Size</th>
                                            <th>Size_cn</th>
                                            <th>Price </th>
                                            <th>English Status</th>
                                            <th>Chinese Status</th>
                                            <th>Max. Selected</th>

                                            <th>Actions</th>
                                        </tr>
                                    </thead>   
                                    <tbody>
                                        <?php $query = $db->query("SELECT * FROM layer_lists WHERE layer_id='$menu_layer_id' "); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($r = $db->fetch_array($query)) { //print_r($r); die;?>
                                                <tr>
                                                    <td><?php echo $r['id']; ?></td>
                                                    <td><?php echo $r['name']; ?></td>
                                                    <td><?php echo $r['name_cn']; ?></td>
                                                <!--    <td><?php echo getData('menu_categories', 'title', $r['menu_cat_id']); ?></td>-->

                                                    <td class="smallFont"><?php echo $r['description']; ?></td>
                                                    <td class="smallFont"><?php echo $r['description_cn']; ?></td>
                                                    <?php
                                                    $size = explode(',', $r['size']);
                                                    $value = '';
                                                    $value_cn = '';
                                                    foreach ($size as $sz) {
                                                        $value = '';
                                                        $value_cn = '';
                                                        if (!empty($sz)) {
                                                            $sizeQuery = $db->query("SELECT `value`, `value_cn` FROM menu_item_sizes WHERE id=$sz");
                                                            $rSize = $db->fetch_array($sizeQuery);
                                                            $value .= $rSize['value'] . ',';
                                                            $value_cn .= $rSize['value_cn'] . ',';
                                                        }
                                                    }
                                                    ?>
                                                    <td><?php echo rtrim($value, ','); ?></td>
                                                    <td><?php echo rtrim($value_cn, ','); ?></td>
                                                    <td><?php echo $r['price']; ?></td>
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
                                                    <td class="center">
                                                        <span class="label">
                                                            <?php echo $r['maximum_selected']; ?>
                                                        </span>
                                                    </td>  
                                                    <td class="center">


                                                        <a class="btn btn-info" href="layers-attribute-edit.php?id=<?php echo $id; ?>&menuid=<?php echo $menu_id; ?>&mi_lid=<?php echo $menu_layer_id; ?>&attribute=<?php echo $r["id"]; ?>" title="Edit">
                                                            <i class="icon-edit icon-white"></i>  
                                                        </a>
                                                        <a class="btn btn-inverse" href="?id=<?php echo $id; ?>&layer=<?php echo $menu_layer_id; ?>&attribute=<?php echo $r["id"]; ?>&status=<?php echo $r['status'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>
                                                        <a class="btn btn-warning" href="?id=<?php echo $id; ?>&layer=<?php echo $menu_layer_id; ?>&attribute=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn'] == 1 ? 0 : 1; ?>" title="Change Status">
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
                                                                            <a class="btn btn-danger" href="?id=<?php echo $id; ?>&layer=<?php echo $menu_layer_id; ?>&attribute=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                                                <i class="icon-trash icon-white"></i> 
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



















                                                    </td>

                                                </tr>
                                            <?php } // endwhile $query loop  ?>
                                        <?php } // $db->affected_rows ?>
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
</html>
