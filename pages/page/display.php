<?php 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
	$query = mysql_query("SELECT * FROM pages WHERE id = $id");
	$page = mysql_fetch_array($query);
?>

	<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <a href="index.php">Home</a>
                <img src="img/title-icon.png" alt="" />
                <a href="#"><?php echo $page['title']; ?></a>
            </div>
        </div>
    </div>
    <div class="row myfonts">
        <div class="page-block col-md-10 well">
            <h2><?php echo $page['title']; ?></h2>
            <div class="innerContent">
                <?php echo $page['content']; ?>
            </div>
        </div>
        <div class="col-md-2">
            <img src="img/keep-up.png" />
            <div class="soc-net"><a href="https://www.facebook.com/ShenzhenEat" target="_blank"><img src="img/fbook1.png" alt="" />Facebook</a></div>
            <div class="soc-net"><a href="https://twitter.com/ShenzhenEat" target="_blank"><img src="img/twiter1.png" alt="" />Twiter</a></div>
        </div>
    </div>
    
</div>


<?php
} else {
	header("Location: index.php");
	exit;
}
?>



