<div class="footer">
    <div class="container">
        <div class="row">
        	<?php 
                $pages = $db->query("SELECT * FROM pages WHERE status=1 ORDER BY id");
                $total = $db->affected_rows;
                $half  = round($total/2);

                if ($db->affected_rows > 0) { 
            ?>
                <div class="span2 offset2">
	                <ul>
					<?php 
                        while($pr=$db->fetch_array($pages)) { 
                            isset($count) ? $count++:$count=1; 
                    ?>
                            <li><a href="page.php?name=<?php echo urlText($pr['title']); ?>"><?php __($pr['title']); ?></a></li>
						<?php if ($count==$half) { ?>
                            	</ul>
                            </div>
                        	<div class="span3">
                            	<ul>
                        <?php } ?>
                    <?php } // end while $pages ?>
	                </ul>
                </div>
            <?php } // $db->affected_rows ?>
            <div class="span3 offset2">
                <div class="soc-link">
                    <div><a href="<?php echo checkFeild(_facebook) ? _facebook:'#'; ?>" target="_blank"><img src="img/fbook.png" alt="" /></a></div>
                    <div><a href="<?php echo checkFeild(_twitter) ? _twitter:'#'; ?>" target="_blank"><img src="img/twiter.png" alt="" /></a></div>
                </div>
                <br />
                <a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备15014754号-1</a>
            </div>
        </div>
    </div>
</div>

<script src="js/static.getclicky.com.js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100728789); }catch(e){}</script>

<?php if (isset($_SESSION['error'])) unset($_SESSION['error'],$_SESSION['msg']); ?>

<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.min"></script>
<script type="text/javascript" src="js/less-1.3.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<!--
<script type="text/javascript" src="js/jquery.dd.min.js"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>
-->
<script src="js/chosen/chosen.jquery.js" type="text/javascript"></script>

    
</body>
</html>
