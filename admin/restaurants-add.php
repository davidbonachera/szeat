<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'restaurants.php',
					'title'	=> 'Restaurants'
				),
				array(
					'link'	=> 'restaurants-add.php',
					'title'	=> 'Add New'
				)
			);
?>
<?php
if ($_POST) {

	if (checkFeild($_POST["name"]) && checkFeild($_POST["address"]) && checkFeild($_POST["comission_type"]) && checkFeild($_POST["comission_value"])) {

    $data["name"] 				  = $_POST["name"];
    $data["name_cn"]        = $_POST["name_cn"];
    $data["address"] 			  = $_POST["address"];
    $data["address_cn"]     = $_POST["address_cn"];
    $data["phone"] 				  = $_POST["phone"];
    $data["description"] 		= $_POST["description"];
    $data["description_cn"] = $_POST["description_cn"];
    $data["minimum_order"]  = (int)$_POST["minimum_order"];
    $data["delivery_fee"]   = (int)$_POST["delivery_fee"];
    $data["comission_type"] = $_POST["comission_type"];
    $data["comission_value"]= $_POST["comission_value"];
    $data["black_list"] 		= $_POST["black_list"];
    $data["notes"] 				  = $_POST["notes"];
    $data["mailing_address"]= $_POST["mailing_address"];
    $data["status"] 			  = $_POST["status"];
    $data["status_cn"]      = $_POST["status_cn"];
    $data["date"] 				  = _nowdt;
		
		if (checkFeild($_FILES['thumbnail']['name'])) {
			$file 			  = new FileUpload();
			$file_name 		= rand(1234567890,time());
			$file->File 	= $_FILES['thumbnail'];
			$file->Path 	= "../resources/restaurants/";
			$file->FileName = $file_name;
			$file->Upload();
			
			$data['thumbnail'] = "resources/restaurants/".$file_name.".".$file->ext;
		}
	
		$restaurant_id = $db->query_insert("restaurants", $data);
		
		if($db->affected_rows > 0){
			
			foreach ($_POST['cuisine_id'] as $val) {
				unset($data);
				$data["restaurant_id"] 		= $restaurant_id;
				$data["cuisine_id"] 		= $val;
				$data["status"] 			= 1;
				$data["date"] 				= _nowdt;
				$restaurant_cuisines = $db->query_insert("restaurants_cuisines", $data);
			}
			
			foreach ($_POST['area_id'] as $val) {
				unset($data);
				$data["restaurant_id"] 		= $restaurant_id;
				$data["area_id"] 			= $val;
				$data["status"] 			= 1;
				$data["date"] 				= _nowdt;
				$restaurant_cuisines = $db->query_insert("restaurants_areas", $data);
			}
			
			foreach ($_POST['building_id'] as $val) {
				unset($data);
				$data["restaurant_id"] 		= $restaurant_id;
				$data["building_id"] 		= $val;
				$data["status"] 			= 1;
				$data["date"] 				= _nowdt;
				$restaurant_cuisines = $db->query_insert("restaurants_buildings", $data);
			}
			
			$_SESSION['msg'] = "Restaurant successfully created.";
			$_SESSION['error'] = false;
			header("Location: restaurants.php");
			exit();
		} else {
			$_SESSION['msg'] = "<strong>Oops!</strong> Restaurant can't be created.";
			$_SESSION['error'] = true;
		}
	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Add New Restaurant</title>
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
                    
                    <div class="row-fluid sortable">
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-plus"></i><span class="break"></span>Add New Restaurant</h2>
                            </div>
                            
                            
                            <?php if(isset($_SESSION['msg'])) { ?>
                            <div class="box-content alerts">
                                <div class="alert alert-<?php echo $_SESSION['error']==true ? 'error':'success'; ?>">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <?php echo $_SESSION['msg']; ?>
                                </div>
                            </div><!--alerts-->
                            <?php } // if isset($_SESSION['msg']) ?>
                            
                            <div class="box-content">
                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="name">* English Restaurant Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="name_cn">Chinese Restaurant Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="name_cn" name="name_cn" type="text">
                                        </div>
                                      </div>                                      
                                      <div class="control-group">
                                        <label class="control-label" for="address">* English Address</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="address" name="address" type="text">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="address_cn">Chinese Address</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="address_cn" name="address_cn" type="text">
                                        </div>
                                      </div>                                      
                                      <div class="control-group">
                                        <label class="control-label" for="phone">Phone No.</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="phone" name="phone" type="text">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="minimum_order">Minimum Order</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="minimum_order" name="minimum_order" type="number" maxlength="3">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="delivery_fee">Delivery Fee</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="delivery_fee" name="delivery_fee" type="number" maxlength="3">
                                        </div>
                                      </div>                                      
                                      <!--
                                      <div class="control-group">
                                        <label class="control-label" for="delivery_time">* Delivery Time</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="delivery_time" name="delivery_time" type="text" value="">
                                          <p class="help-block">Format: 10:30am - 11:00pm</p>
                                        </div>
                                      </div>
                                      -->
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="description">* English Description</label>
                                          <div class="controls">
                                            <textarea class="cleditor" id="description" name="description" rows="3"></textarea>
                                          </div>
                                      </div>
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="description_cn">Chinese Description</label>
                                          <div class="controls">
                                            <textarea class="cleditor" id="description_cn" name="description_cn" rows="3"></textarea>
                                          </div>
                                      </div>                                      
                                      <div class="control-group">
                                        <label class="control-label">Thumbnail</label>
                                        <div class="controls">
                                          <input type="file" name="thumbnail" id="thumbnail" />
                                          <p class="help-block">Image representing the restaurant</p>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="comission_value">* Comission per order</label>
                                        <div class="controls">
                                          <div class="input-append">
                                            <input class="input-xlarge focused" id="comission_value" name="comission_value" type="text" value="">
                                            <select id="comission_type" name="comission_type" style="width:120px;">
                                                <option selected value="percent">Percentage</option>
                                                <option value="fixed">Fixed Price</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="cuisine_id">* Cuisines</label>
                                        <div class="controls">
                                          <select id="cuisine_id" name="cuisine_id[]" multiple data-rel="chosen">
                                            <?php $cquery = $db->query("SELECT * FROM cuisines WHERE status=1 ORDER BY title"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while ($cr=$db->fetch_array($cquery)) { ?>
                                                	<option value="<?php echo $cr['id']; ?>"><?php echo $cr['title']; ?></option>
                                                <?php } // endwhile $cr ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="area_id">* Areas</label>
                                        <div class="controls">
                                          <select id="area_id" name="area_id[]" multiple data-rel="chosen">
                                            <?php $aquery = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while ($ar=$db->fetch_array($aquery)) { ?>
                                                	<option value="<?php echo $ar['id']; ?>"><?php echo $ar['title']; ?></option>
                                                <?php } // endwhile $cr ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="building_id">* Buildings</label>
                                        <div class="controls">
                                          <select id="building_id" name="building_id[]" multiple data-rel="chosen">
                                            <?php $bquery = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title"); ?>
                                            <?php if ($db->affected_rows > 0) { ?>
                                            	<?php while ($br=$db->fetch_array($bquery)) { ?>
                                                	<option value="<?php echo $br['id']; ?>"><?php echo $br['title']; ?></option>
                                                <?php } // endwhile $cr ?>
                                            <?php } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label">Blacklist</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="black_list" id="black_list1" value="1">
                                            Yes
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="black_list" id="black_list2" value="0" checked="">
                                            No
                                          </label>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="notes">Notes</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="notes" name="notes" rows="3"></textarea>
                                          <p class="help-block">Visible only to Administrators</p>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="notes">Mailing Address</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="mailing_address" name="mailing_address" rows="3"></textarea>
                                          <p class="help-block">This address will be printed on Invoices</p>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label">English Status</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="status" id="status1" value="1" checked="">
                                            Enable
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="status" id="status2" value="0">
                                            Disable
                                          </label>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label">Chinese Status</label>
                                        <div class="controls">
                                          <label class="radio">
                                            <input type="radio" name="status_cn" id="status_cn1" value="1" checked="">
                                            Enable
                                          </label>
                                          <div style="clear:both"></div>
                                          <label class="radio">
                                            <input type="radio" name="status_cn" id="status_cn2" value="0">
                                            Disable
                                          </label>
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
                    
                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
		<div class="clearfix">&nbsp;</div>
		<?php include("include/footer.php"); ?>		
	</div><!--/.fluid-container-->
<?php include("include/footer-inc.php"); ?>
<?php unset($_SESSION['msg'], $_SESSION['error']); ?>
</body>
</html>
