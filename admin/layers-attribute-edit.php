<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php
$id = addslashes($_GET['id']);
$layerid = addslashes($_GET['mi_lid']);

$mid = addslashes($_GET['menuid']);
$attr_id = addslashes($_GET['attribute']);
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
        'title' => 'Categories &amp; Menu Items'
    ),
    array(
        'link' => '#',
        'title' => 'Edit Item'
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

if (isset($_POST) && !empty($_POST)) {
    //echo "<pre>";print_r($_POST );die();

    if (checkFeild(checkFeild($_POST['name']) && checkFeild($_POST['price']))) {






        $data['price'] = $_POST['price'];
        $data['name'] = ucwords(strtolower($_POST['name']));
        $data['name_cn'] = ucwords(strtolower($_POST['name_cn']));
        $data['description'] = $_POST['description'];
        $data['description_cn'] = $_POST['description_cn'];
        $data['maximum_selected'] = $_POST['maximum_selected'];
        $data["date"] = _nowdt;
        //	echo "<pre>";print_r($data );die();

        if ($db->query_update("layer_lists", $data, "id='$attr_id'")) {
            $_SESSION['error'] = false;
            $_SESSION['msg'] = "Layer Attributes successfully edited";
            header("Location: layers_list_show.php?id=$id&menuid=$mid&mi_lid=$layerid");

            exit();
        } else {
            $_SESSION['error'] = true;
            $_SESSION['item'] = "item";
            $_SESSION['msg'] = "Layer Attributes couldn't be added";
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['item'] = "item";
        $_SESSION['msg'] = "All * marked fields are quired.";
    }
}

$query = $db->query_first("SELECT * FROM layer_lists WHERE id='$attr_id'");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Edit Menu Item Layers</title>
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
                                <h2><i class="icon-glass"></i><span class="break"></span>Edit Menu Item Layers</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                    <input type="hidden" name="form" id="form" value="menu" />
                                    <fieldset>
                                        <!--  <div class="control-group">
                                            <label class="control-label" for="menu_cat_id">* Category</label>
                                            <div class="controls">
                                              <select id="menu_cat_id" name="menu_cat_id" data-rel="chosen">
                                        <?php $areas = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title ASC"); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($ar = $db->fetch_array($areas)) { ?>
                                                                    <option value="<?php echo $ar['id']; ?>" <?php echo $query['menu_cat_id'] == $ar['id'] ? 'selected' : NULL; ?>>
                                                <?php echo $ar['title']; ?>
                                                                </option>
                                            <?php } // endwhile $areas ?>
                                        <?php } // $db->affected_rows ?>
                                              </select>
                                            </div>-->
                                        <div class="control-group">
                                            <label class="control-label" for="name">* English Name</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="name" name="name" type="text" value="<?php echo $query['name']; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="name_cn">Chinese Name</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="name_cn" name="name_cn" type="text" value="<?php echo $query['name_cn']; ?>">
                                            </div>
                                        </div>                                      
                                        <div class="control-group">
                                            <label class="control-label" for="description">Description</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge focused" id="description" name="description" rows="3"><?php echo $query['description']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="description_cn">Chinese Description</label>
                                            <div class="controls">
                                                <textarea class="input-xlarge focused" id="description_cn" name="description_cn" rows="3"><?php echo $query['description_cn']; ?></textarea>
                                            </div>
                                        </div>   
                                        <div class="control-group">
                                            <label class="control-label" for="maximum_selected">Limit</label>
                                            <div class="controls">
                                                <input type="input" class="input-xlarge focused" id="maximum_selected" name="maximum_selected" type="text" required="required" value="<?php echo $query['maximum_selected']; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="price">* Price</label>
                                            <div class="controls">
                                                <div class="input-prepend">
                                                    <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="<?php echo $query['price']; ?>">
                                                </div>
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

                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
            <div class="clearfix">&nbsp;</div>
            <?php include("include/footer.php"); ?>		
        </div><!--/.fluid-container-->
        <?php include("include/footer-inc.php"); ?>
        <?php unset($_SESSION['msg'], $_SESSION['error']); ?>
    </body>
</html>
