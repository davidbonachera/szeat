<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $id = addslashes($_GET['id']); ?>
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
    if ($_POST['form'] == 'category') {
        if (checkFeild($_POST['title'])) {

            $data['restaurant_id'] = $id;
            $data['title'] = ucwords(strtolower($_POST['title']));
            $data['description'] = htmlentities($_POST['description']);
            $data['title_cn'] = ucwords(strtolower($_POST['title_cn']));
            $data['description_cn'] = htmlentities($_POST['description_cn']);
            $data['prior'] = is_numeric($_POST['prior']) ? $_POST['prior'] : 0;
            $data["status"] = 1;
            $data["status_cn"] = 1;
            $data["date"] = _nowdt;

            if ($db->query_insert("menu_categories", $data)) {
                $_SESSION['error'] = false;
                $_SESSION['item'] = "category";
                $_SESSION['msg'] = "Category successfully added";
                header("Location: menus.php?id=$id");
                exit();
            } else {
                $_SESSION['item'] = "category";
                $_SESSION['error'] = true;
                $_SESSION['msg'] = "Category couldn't be added";
            }
        } else {
            $_SESSION['error'] = true;
            $_SESSION['item'] = "category";
            $_SESSION['msg'] = "Please enter category title.";
        }
    } elseif ($_POST['form'] == 'menu') {
        if (checkFeild($_POST['menu_cat_id']) || checkFeild($_POST['item_number']) || checkFeild($_POST['name']) || checkFeild($_POST['value']) || checkFeild($_POST['price'])) {

            $data['restaurant_id'] = $id;
            $data['menu_cat_id'] = $_POST['menu_cat_id'];
            $data['item_number'] = $_POST['item_number'];
            $data['name'] = ucwords(strtolower($_POST['name']));
            $data['name_cn'] = ucwords(strtolower($_POST['name_cn']));
            $data['description'] = $_POST['description'];
            $data['description_cn'] = $_POST['description_cn'];
            $data['price'] = $_POST['price'];
            $data["status"] = 1;
            $data["status_cn"] = 1;
            $data["date"] = _nowdt;

            if ($db->query_insert("menu_items", $data)) {
                $_SESSION['error'] = false;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = "Menu Item successfully added";
                header("Location: menus.php?id=$id");
                exit();
            } else {
                $_SESSION['error'] = true;
                $_SESSION['item'] = "item";
                $_SESSION['msg'] = "Menu Item couldn't be added";
            }
        } else {
            $_SESSION['error'] = true;
            $_SESSION['item'] = "item";
            $_SESSION['msg'] = "All * marked fields are quired.";
        }
    }
}

if (isset($_GET['c_id'])) {
    $dhID = addslashes($_GET['c_id']);

    if (isset($_GET['delete'])) {
        if ($db->query("DELETE FROM menu_categories WHERE id='$dhID' LIMIT 1")) {
            $_SESSION['msg'] = "Category successfully deleted.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Category can't be deleted.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    } elseif (isset($_GET['status'])) {

        $data['status'] = $_GET['status'];

        if ($db->query_update("menu_categories", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Category status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Menu status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    } elseif (isset($_GET['status_cn'])) {

        $data['status_cn'] = $_GET['status_cn'];

        if ($db->query_update("menu_categories", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Category status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Menu status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    }
} elseif (isset($_GET['m_id'])) {
    $dhID = addslashes($_GET['m_id']);

    if (isset($_GET['delete'])) {
        if ($db->query("DELETE FROM menu_items WHERE id='$dhID' LIMIT 1")) {
            $_SESSION['msg'] = "Menu Item successfully deleted.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item can't be deleted.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    } elseif (isset($_GET['status'])) {

        $data['status'] = $_GET['status'];

        if ($db->query_update("menu_items", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Menu Item status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    } elseif (isset($_GET['status_cn'])) {

        $data['status_cn'] = $_GET['status_cn'];

        if ($db->query_update("menu_items", $data, "id='$dhID'")) {
            $_SESSION['msg'] = "Menu Item status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: menus.php?id=$id");
            exit();
        } else {
            $_SESSION['msg'] = "Menu Item status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: menus.php?id=$id");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Menus</title>
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
                    <?php } // if isset($_SESSION['msg'])  ?>

                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Categories</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-<?php echo $_SESSION['item'] == "category" ? "up" : "down"; ?>"></i></a>
                                </div>
                            </div>
                            <div class="box-content" style="display:<?php echo $_SESSION['item'] == "category" ? "block" : "none"; ?>">
                                <form class="form-horizontal" method="post">
                                    <input type="hidden" name="form" id="form" value="category" />
                                    <fieldset>
                                        <div class="control-group">
                                            <label class="control-label" for="title">* English Title</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="title" name="title" type="text" value="">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="title_cn">Chinese Title</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="title_cn" name="title_cn" type="text" value="">
                                            </div>
                                        </div>                                      
                                        <div class="control-group">
                                            <label class="control-label" for="description">English Description</label>
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
                                            <label class="control-label" for="prior">* Sort Order</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="prior" name="prior" type="text" value="">
                                                <p class="smallFont">Only numeric values are allowed. Lowest number will show category on top.</p>
                                            </div>
                                        </div>
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
                                <h2><i class="icon-glass"></i><span class="break"></span>Menu Categories</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-<?php echo $_SESSION['item'] == "category" ? "up" : "down"; ?>"></i></a>
                                </div>
                            </div>

                            <div class="box-content" style="display:<?php echo $_SESSION['item'] == "category" ? "block" : "none"; ?>;">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                    <thead>
                                        <tr>
                                            <th class="hidden"></th>
                                            <th>Title</th>
                                            <th>Chinese Title</th>
                                            <th>Description</th>
                                            <th>Chinese Description</th>                                          
                                            <th>Sort Order</th>
                                            <th>Items</th>
                                            <th>Date</th>
                                            <th>English Status</th>
                                            <th>Chinese Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>   
                                    <tbody>
                                        <?php $query = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title"); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($r = $db->fetch_array($query)) { ?>
                                                <tr>
                                                    <td class="hidden"><?php echo $r['prior']; ?></td>
                                                    <td><?php echo $r['title']; ?></td>
                                                    <td><?php echo $r['title_cn']; ?></td>
                                                    <td><?php echo $r['description']; ?></td>
                                                    <td><?php echo $r['description_cn']; ?></td>
                                                    <td><?php echo $r['prior']; ?></td>
                                                    <td><?php echo recordsCounter('menu_items', 'menu_cat_id', $r['id']); ?></td>
                                                    <td><?php echo date("M d, Y", strtotime($r['date'])); ?></td>
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
                                                        <a class="btn btn-info" href="menu-category-edit.php?id=<?php echo $id; ?>&c_id=<?php echo $r["id"]; ?>" title="Edit">
                                                            <i class="icon-edit icon-white"></i>  
                                                        </a>
                                                        <a class="btn btn-inverse" href="?id=<?php echo $id; ?>&c_id=<?php echo $r["id"]; ?>&status=<?php echo $r['status'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>
                                                        <a class="btn btn-warning" href="?id=<?php echo $id; ?>&c_id=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status_cn'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>                                                    
                                                        <a class="btn btn-danger" href="?id=<?php echo $id; ?>&c_id=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                            <i class="icon-trash icon-white"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } // endwhile $query loop ?>
                                        <?php } // $db->affected_rows  ?>
                                    </tbody>
                                </table>            
                            </div>
                        </div><!--/span-->

                    </div><!--/row-->

                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Menu Items</h2>
                                <div class="box-icon">
                                    <a href="#" class="btn-minimize"><i class="icon-chevron-<?php echo $_SESSION['item'] == "item" ? "up" : "down"; ?>"></i></a>
                                </div>
                            </div>
                            <div class="box-content" style="display:<?php echo $_SESSION['item'] == "item" ? "block" : "none"; ?>;">
                                <form class="form-horizontal" method="post">
                                    <input type="hidden" name="form" id="form" value="menu" />
                                    <fieldset>
                                        <div class="control-group">
                                            <label class="control-label" for="menu_cat_id">* Category</label>
                                            <div class="controls">
                                                <select id="menu_cat_id" name="menu_cat_id" data-rel="chosen">
                                                    <?php $areas = $db->query("SELECT * FROM menu_categories WHERE restaurant_id='$id' ORDER BY title ASC"); ?>
                                                    <?php if ($db->affected_rows > 0) { ?>
                                                        <?php while ($ar = $db->fetch_array($areas)) { ?>
                                                            <option value="<?php echo $ar['id']; ?>"><?php echo $ar['title']; ?></option>
                                                        <?php } // endwhile $areas ?>
                                                    <?php } // $db->affected_rows  ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="item_number">* Item Number</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="item_number" name="item_number" type="text" value="">
                                            </div>
                                        </div>
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
                                        <!--
                                        <div class="control-group">
                                          <label class="control-label" for="value">* Quantity</label>
                                          <div class="controls">
                                            <input class="input-xlarge focused" id="value" name="value" type="text" value="">
                                          </div>
                                        </div>
                                        -->
                                        <div class="control-group">
                                            <label class="control-label" for="price">* Price</label>
                                            <div class="controls">
                                                <div class="input-prepend">
                                                    <span class="add-on"><?php echo _priceSymbol; ?></span><input id="price" name="price" type="text" value="">
                                                </div>
                                            </div>
                                        </div>
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
                                <h2><i class="icon-leaf"></i><span class="break"></span>Menu Items</h2>
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
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Description_cn</th>
                                            <th>Price</th>
                                            <th>English Status</th>
                                            <th>Chinese Status</th>
                                            <th>Has Layer</th>					
                                            <th>Actions</th>
                                        </tr>
                                    </thead>   
                                    <tbody>
                                        <?php $query = $db->query("SELECT * FROM menu_items WHERE restaurant_id='$id' ORDER BY item_number"); ?>
                                        <?php if ($db->affected_rows > 0) { ?>
                                            <?php while ($r = $db->fetch_array($query)) { ?>
                                                <tr>
                                                    <td><?php echo $r['item_number']; ?></td>
                                                    <td><?php echo $r['name']; ?></td>
                                                    <td><?php echo $r['name_cn']; ?></td>
                                                    <td><?php echo getData('menu_categories', 'title', $r['menu_cat_id']); ?></td>
                                                    <td class="smallFont"><?php echo $r['description']; ?></td>
                                                    <td class="smallFont"><?php echo $r['description_cn']; ?></td>
                                                    <td><?php echo _priceSymbol; ?><?php echo $r['price']; ?></td>
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

                                                    <td>
                                                        <?php
                                                        $mmid = $r["id"];
                                                        $reid = $r['restaurant_id'];
                                                        $resp_column = $db->query("SELECT  * FROM menu_item_layers WHERE restaurant_id='$reid' AND menu_item_id='$mmid'");
                                                        if ($db->affected_rows > 0) {
                                                            echo "<span class='label label-success'>Yes</span>";
                                                        } else {
                                                            echo "<span class='label'>No</span>";
                                                        }
                                                        ?>
                                                    </td>									

                                                    <td class="center">
                                                        <a class="btn btn-info" href="menu-item-edit.php?id=<?php echo $id; ?>&m_id=<?php echo $r["id"]; ?>" title="Edit">
                                                            <i class="icon-edit icon-white"></i>  
                                                        </a>
                                                        <a class="btn btn-inverse" href="?id=<?php echo $id; ?>&m_id=<?php echo $r["id"]; ?>&status=<?php echo $r['status'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a>
                                                        <a class="btn btn-warning" href="?id=<?php echo $id; ?>&m_id=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn'] == 1 ? 0 : 1; ?>" title="Change Status">
                                                            <i class="icon-eye-<?php echo $r['status_cn'] == 1 ? 'open' : 'close'; ?> icon-white"></i> 
                                                        </a> 
                                                        <a class="btn btn-info" href="menu_layers_show.php?id=<?php echo $id; ?>&m_id=<?php echo $r["id"]; ?>" title="View Layer ">
                                                            <i class="icon-th-large icon-white"></i>  
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
                                                                            <a class="btn btn-danger" href="?id=<?php echo $id; ?>&m_id=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                                                <i class="icon-trash icon-white"></i> 
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>





                                                        <a class="btn btn-success" href="menus-sizes.php?menu_item=<?php echo $r["id"]; ?>&id=<?php echo $id; ?>" title="Detailed Price">
                                                            <i class="icon-tags icon-white"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } // endwhile $query loop ?>
                                        <?php } // $db->affected_rows  ?>
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
