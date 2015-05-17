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
					'link'	=> 'pages.php',
					'title'	=> 'Pages'
				),
				array(
					'link'	=> 'pages-add.php',
					'title'	=> 'Add New'
				)
			);
?>
<?php
if ($_POST) {
	if (checkFeild($_POST["title"]) && checkFeild($_POST["content"])) {

		$data["title"] 			= $_POST["title"];
        $data["title_cn"]      = $_POST["title_cn"];
		$data["keywords"] 		= $_POST["keywords"];
		$data["description"]	= $_POST["description"];
		$data["content"] 		= $_POST["content"];
        $data["content_cn"]    = $_POST["content_cn"];
		$data["status"] 		= $_POST["status"];
        $data["status_cn"]     = $_POST["status_cn"];
		$data["date"] 			= _nowdt;
	
		$pid = $db->query_insert("pages", $data);
		
		if($db->affected_rows > 0){
			$_SESSION['msg'] = "Page successfully created.";
			$_SESSION['error'] = false;
			header("Location: pages.php");
			exit();
		} else {
			$_SESSION['msg'] = "<strong>Oops!</strong> Page can't be created.";
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
	<title>Add New Page</title>
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
                                <h2><i class="icon-plus"></i><span class="break"></span>Add New Page</h2>
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
                                            <label class="control-label" for="title">* English Title</label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="title" name="title" type="text" value="">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="title_cn">Chinese Title </label>
                                            <div class="controls">
                                                <input class="input-xlarge focused" id="title_cn" name="title_cn" type="text" value="">
                                            </div>
                                        </div>

                                    


                                      <div class="control-group">
                                        <label class="control-label" for="keywords">Keywords</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="keywords" name="keywords" rows="3"></textarea>
                                        </div>
                                      </div>
                                      
                                      <div class="control-group">
                                        <label class="control-label" for="description">Description</label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="description" name="description" rows="3"></textarea>
                                        </div>
                                      </div>
                                      
                                      <div class="control-group hidden-phone">
                                          <label class="control-label" for="content">* English Content</label>
                                          <div class="controls">
                                            <textarea class="cleditor" id="content" name="content" rows="3"></textarea>
                                          </div>
                                      </div>

                                    <div class="control-group hidden-phone">
                                        <label class="control-label" for="content_cn">Chinese Content</label>
                                        <div class="controls">
                                            <textarea class="cleditor" id="content_cn" name="content_cn" rows="3"></textarea>
                                        </div>
                                    </div>
                                      
                                    <div class="control-group">
                                        <label class="control-label">English Status</label>
                                        <div class="controls">
                                            <label class="radio"><input type="radio" name="status" id="status1" value="1" checked="">Enable</label>
                                            <div style="clear:both"></div>
                                            <label class="radio"><input type="radio" name="status" id="status2" value="0">Disable</label>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Chinese Status</label>
                                        <div class="controls">
                                            <label class="radio"><input type="radio" name="status_cn" id="status_cn1" value="1" checked="">Enable</label>
                                            <div style="clear:both"></div>
                                            <label class="radio"><input type="radio" name="status_cn" id="status_cn2" value="0">Disable</label>
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
