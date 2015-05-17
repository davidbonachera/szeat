<?php 
	$query = mysql_query("SELECT * FROM test");
	$page = mysql_fetch_array($query);
?>

<div class="container">

    <div class="row myfonts">
        <div class="page-block col-md-10 well">

            <?php echo ($lang=='cn'?($page['title_cn']==""?$page['title']:$page['title_cn']):$page['title']); ?>


        </div>
    </div>
    
</div>
