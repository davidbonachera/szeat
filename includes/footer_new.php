<footer class="footer">
    <div class="container">
        
	    <?php 

        $pages = $db->query("SELECT * FROM pages WHERE status=1 ORDER BY id");
        $total = $db->affected_rows;
        $half  = round($total/2);

        if ($db->affected_rows > 0) { ?>
        
            <div class="col-md-3">
               <ul>
                    <?php 
                    while($pr=$db->fetch_array($pages)) { 
                        isset($count) ? $count++:$count=1; 
                        ?>
                        <li><a href="page.php?name=<?php echo urlText($pr['title']); ?>"><?php __($pr['title']); ?></a></li>
                        <?php if ($count==$half) { ?>
                            </ul>
                            </div>
                	       <div class="col-md-3">
                    	       <ul>
                                <?php } ?>
                    <?php } // end while $pages ?>
               </ul>
            </div>
        <?php } // $db->affected_rows ?>
    
        <div class="col-sm-3">
            <div class="soc-link">
                <a href="https://www.facebook.com/ShenzhenEat" target="_blank"><img src="img/fbook.png" alt="" /></a>
                <a href="https://twitter.com/ShenzhenEat" target="_blank"><img src="img/twiter.png" alt="" /></a>
            </div>
            <br />
            <a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备15014754号-1</a>
            <p>&copy; <?php echo date("Y"); ?> ShenzhenEat.com</p>
        </div>
    
    </div>
</footer>

