<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php 
if (isset($_GET['name'])) {
	$name = $db->escape(html_entity_decode($_GET['name']));
	$str  = str_replace('+',' ', $name);
	$page = $db->query_first("SELECT * FROM pages WHERE title LIKE '%$str%' AND status=1 LIMIT 1");
	if ($db->affected_rows==0) header("Location: index.php?404");
} else {
	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="keywords" content="<?php echo htmlentities($page['keywords']); ?>" />
    <meta http-equiv="description" content="<?php echo htmlentities($page['description']); ?>" />
    <title><?php echo htmlentities($page['title']); ?>  <?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#"><?php __($page['title']); ?></a>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="page-block span10">
				<h2><?php __($page['title']); ?></h2>
				<div class="innerContent">
					<?php echo $page['content']; ?>
                </div>
            </div>
        	<div class="span2">
            	<img src="img/keep-up.png" />
                <div class="soc-net"><a href="<?php echo checkFeild(_facebook) ? _facebook:'#'; ?>"><img src="img/fbook1.png" alt="" />Facebook</a></div>
                <div class="soc-net"><a href="<?php echo checkFeild(_twitter) ? _twitter:'#'; ?>"><img src="img/twiter1.png" alt="" />Twiter</a></div>
            </div>
        </div>
        
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>
