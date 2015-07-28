<div>
    <hr>
    <ul class="breadcrumb">
        <li><a href="../">Home</a></li>
        <?php foreach ($pData as $page) { ?>
        	<li><span class="divider">/</span></li>
        	<li><a href="<?php echo $page['link']; ?>"><?php echo $page['title']; ?></a></li>
        <?php } // foreach ?>
    </ul>
    <hr />
</div>
