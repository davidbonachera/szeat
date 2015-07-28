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
					'link'	=> 'email-templates.php',
					'title'	=> 'Email Templates'
				),
				array(
					'link'	=> 'email-templates-edit.php?id='.$id,
					'title'	=> 'Edit Template'
				)
			);
?>
<?php
if (!checkFeild($id)) {
	$_SESSION['msg'] 	= "Template Not Found.";
	$_SESSION['error'] 	= false;
	header("Location: email-templates.php");
	exit();
}

if ($_POST) {
	if (checkFeild($_POST["from_email"]) && checkFeild($_POST["subject"]) && checkFeild($_POST["body"])) {
		if (validEmail($_POST["from_email"])) {
			
			$data["from_name"] 			= $_POST["from_name"];
			$data["from_email"] 		= $_POST["from_email"];
			$data["subject"]			= $_POST["subject"];
			$data["body"] 				= $_POST["body"];

			$data["subject_cn"]			= $_POST["subject_cn"];
			$data["body_cn"] 			= $_POST["body_cn"];
		
			$db->query_update("email_templates", $data, "id='$id'");
			if($db->affected_rows > 0){
				$_SESSION['msg'] = "Template successfully updated.";
				$_SESSION['error'] = false;
				header("Location: email-templates-edit.php?id=$id");
				exit();
			} else {
				$_SESSION['msg'] = "<strong>Oops!</strong> Template wasn't updated.";
				$_SESSION['error'] = true;
				header("Location: email-templates-edit.php?id=$id");
				exit();
			}
			
		} else {
			$_SESSION['msg'] = "Please enter a valid email address.";
			$_SESSION['error'] = true;
		}
	} else {
		$_SESSION['msg'] = "All * marked fields are required.";
		$_SESSION['error'] = true;
	}
}

$query = $db->query_first("SELECT * FROM email_templates WHERE id='$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit Email Template</title>
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
                                <h2><i class="icon-edit"></i><span class="break"></span>Edit Email Template</h2>
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
                                <form class="form-horizontal" method="post">
                                    <fieldset>
                                      <div class="control-group">
                                        <label class="control-label" for="restaurant_id">Template</label>
                                        <div class="controls">
                                          <span class="input-xlarge uneditable-input"><?php echo ucwords(str_replace("_"," ",$query['name'])); ?></span>
                                        </div>
                                      </div>
                                      
                                      <div class="control-group">
                                        <label class="control-label" for="from_name">Sender Name</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="from_name" name="from_name" type="text" value="<?php echo $query['from_name']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="from_email">* Sender Email</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="from_email" name="from_email" type="text" value="<?php echo $query['from_email']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="subject">* Subject (EN)</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="subject" name="subject" type="text" value="<?php echo $query['subject']; ?>">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="subject_cn">* Subject (CN)</label>
                                        <div class="controls">
                                          <input class="input-xlarge focused" id="subject_cn" name="subject_cn" type="text" value="<?php echo $query['subject_cn']; ?>">
                                        </div>
                                      </div>                                      
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="body">* BODY (EN)</label>
                                          <div class="controls">
                                            <textarea class="cleditor" id="body" name="body" rows="3"><?php echo $query['body']; ?></textarea>
                                            <span class="help-inline">
                                            	<!-- <br /><strong>Predefined Vars:</strong><br /> -->
												<?php $vars = explode(",", $query['vars']); ?>
                                                <ul>
                                                	<?php //foreach ($vars as $key=>$var) echo '<li>'.$var.'</li>'; ?>
                                                </ul>
                                            </span>
                                          </div>
                                      </div>
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="body_cn">* BODY (CN)</label>
                                          <div class="controls">
                                            <textarea class="cleditor" id="body_cn" name="body_cn" rows="3"><?php echo $query['body_cn']; ?></textarea>
                                            <span class="help-inline">
                                            	<!-- <br /><strong>Predefined Vars:</strong><br /> -->
												<?php  $vars = explode(",", $query['vars']); ?>
                                                <ul>
                                                	<?php  //foreach ($vars as $key=>$var) echo '<li>'.$var.'</li>'; ?>
                                                </ul>
                                            </span>
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
