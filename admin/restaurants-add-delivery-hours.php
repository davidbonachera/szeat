<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php $id = addslashes($_GET['id']); ?>
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
					'link'	=> 'restaurants-add-delivery-hours.php?id='.$id,
					'title'	=> 'Delivery Hours'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "Restaurant Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: restaurants.php");
	exit();
}
if ($_POST) {
	if (checkFeild($_POST['start'])) {
		if (checkFeild($_POST['end'])) {
			if (checkFeild($_POST['day'][0])) {
				
				foreach ($_POST['day'] as $dayVal) {
					
					$data['restaurant_id'] 	= $id;
					$data['day'] 			= strtoupper($dayVal);
					$data['start'] 			= date("Y-m-d H:i:s", strtotime($_POST['start']));
					$data['end'] 			= date("Y-m-d H:i:s", strtotime($_POST['end']));
					$data["status"] 		= 1;
					$data["date"] 			= _nowdt;
					
					if ($db->query_insert("delivery_times",$data)) {
						$errors[] = 'no';
					} else {
						$errors[] = 'yes';
					}
				}

				if (in_array('no',$errors)) {
					$_SESSION['error'] = false;
					$_SESSION['msg'] = "Delievery hours successfully added";
					header("Location: restaurants-add-delivery-hours.php?id=$id");
					exit();
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg'] = "Delievery hours couldn't be added";
				}

			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg'] = "you must select a day of delievery hours.";
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg'] = "Please enter end time of delivery hours.";
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg'] = "Please enter start time of delivery hours.";
	}
}

if (isset($_GET['dh_id'])) {
	$dhID = addslashes($_GET['dh_id']);
	
	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM delivery_times WHERE id='$dhID' LIMIT 1")) {
			$_SESSION['msg'] = "Delivery Hours successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: restaurants-add-delivery-hours.php?id=$id");
			exit();
		} else {
			$_SESSION['msg'] = "Delivery Hours can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: restaurants-add-delivery-hours.php?id=$id");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("delivery_times", $data, "id='$dhID'")) {
			$_SESSION['msg'] = "Delivery Hours status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: restaurants-add-delivery-hours.php?id=$id");
			exit();
		} else {
			$_SESSION['msg'] = "Delivery Hours status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: restaurants-add-delivery-hours.php?id=$id");
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Delivery Hours</title>
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
                                <h2><i class="icon-plus"></i><span class="break"></span>Add Delivery Hours</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="post">
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="day">* Day(s)</label>
                                        <div class="controls">
                                          <select id="day" name="day[]" multiple data-rel="chosen">
                                            <option></option>
                                            <option value="sunday">sunday</option>
                                            <option value="monday">monday</option>
                                            <option value="tuesday">tuesday</option>
                                            <option value="wednesday">wednesday</option>
                                            <option value="thursday">thursday</option>
                                            <option value="friday">friday</option>
                                            <option value="saturday">saturday</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="start">* Start Time</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="start" name="start" type="text" value="">
                                           <p class="help-block">Format: 10:30am</p>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="end">* End Time</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="end" name="end" type="text" value="">
                                          <p class="help-block">Format: 11:00pm</p>
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
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-time"></i><span class="break"></span>Delivery Hours</h2>
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
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                  <thead>
                                      <tr>
                                      	  <th>Date</th>
                                          <th>Day</th>
                                          <th>Start</th>
                                          <th>End</th>
                                          <th>Status</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM delivery_times WHERE restaurant_id='$id' ORDER BY day, start"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
                                        <?php 
											while($dr=$db->fetch_array($query)) {
												isset($i) ? $i++:$i=0;
												$del_hours[$i] = $dr;
												$del_hours[$i]['date'] = dayDate($dr['day']);
											} // while loop
											$del_hours = array_orderby($del_hours, 'date', SORT_ASC);
										?>
                                        <?php foreach ($del_hours as $r) { ?>
                                            <tr>
                                            	<td><?php echo $r['date']; ?></td>
                                                <td><?php echo ucfirst(strtolower($r['day'])); ?></td>
                                                <td><?php echo date("h:ia",strtotime($r['start'])); ?></td>
                                                <td><?php echo date("h:ia",strtotime($r['end'])); ?></td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status']==1 ? 'label-success' : NULL; ?>">
                                                    	<?php echo $r['status']==1 ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <a class="btn btn-inverse" href="?id=<?php echo $id; ?>&dh_id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>
                                                    <a class="btn btn-danger" href="?id=<?php echo $id; ?>&dh_id=<?php echo $r["id"]; ?>&delete" title="Delete">
                                                        <i class="icon-trash icon-white"></i> 
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } // endwhile $query loop ?>
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
<?php unset($_SESSION['msg'], $_SESSION['error']); ?>
</body>
</html>
