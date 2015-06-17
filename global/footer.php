<footer class="footer">
        <div class="container">
                        
            <?php

                $pages = $db->query("SELECT * FROM pages WHERE $status=1 ORDER BY id");

                $total = $db->affected_rows;
                $half  = round($total/2);

                if ($db->affected_rows > 0) { 
                    echo '<div class="col-sm-2">';
                        echo '<ul>';
                            while($pr=$db->fetch_array($pages)) {
                                isset($count) ? $count++:$count=1; ?>

                                <li><a href="index.php?page=page&id=<?php echo urlText($pr['id']); ?>"><?php echo ($lang=='cn'?($pr['title_cn']==""?$pr['title']:$pr['title_cn']):$pr['title']); ?></a></li>

                                <?php if ($count==$half) { ?>
                                    </ul>
                                    </div>
                                    <div class="col-sm-6">
                                    <ul>
                                <?php }
                            }
                        echo '</ul>';
                    echo '</div>';
                }

            ?>

            <div class="col-sm-4">
                <div class="soc-link">
                    <a href="https://www.facebook.com/ShenzhenEat" target="_blank"><img src="img/fbook.png" alt="" /></a>
                    <a href="https://twitter.com/ShenzhenEat" target="_blank"><img src="img/twiter.png" alt="" /></a>
                </div>
                <div style="margin-top:0.5em;">
                    <a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备15014754号-1</a>
                    <p>Web Design by <a href="mailto:davidjennings73@gmail.com">David Jennings</a><br>&copy; <?php echo date("Y"); ?> ShenzhenEat.com</p>
                </div>
            </div>
            
        </div>
</footer>

