<div class="container main">
	<div class="row">
    	<div class="span12">
        	<div class="contet-title">Order your delivery online</div>
            <div class="content">
            	<div class="home-form">
                	
                    <form method="get" action="search.php" id="searchForm">
                    	<div class="home-fotm-box">
                        	<label>Your area?</label>
                            <article class="home-fotm-item">
                                <select name="area" id="area" class="styled">
                                    <option value="">Select Area</option>
									<?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($areas)) { ?>
                                    	<option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </article>
                        </div>
                    	<div class="home-fotm-box">
                        	<label>Your building?</label>
                            <article class="home-fotm-item">
                                <select name="building" id="building" class="styled">
                                    <option value="">Select Building</option>
									<?php $buildings = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($buildings)) { ?>
                                        <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </article>
                        </div>
                        <div class="home-fotm-box">
                        	<label>What do you fancy?</label>
                            <article class="home-fotm-item">
                                <select name="cuisines" id="cuisines" class="styled">
                                    <option value="">Show Everything</option>
                                    <?php $cuisines = $db->query("SELECT * FROM cuisines WHERE status=1 ORDER BY title ASC"); ?>
                                    <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                    	<option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                    <?php } // while $areas loop ?>
                                </select>
                            </article>
                        </div>
                        <div class="home-fotm-box">
                        	<input type="submit" value="Find me a delivery" />
                        </div>
                    </form>
                    
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="HomePageError">&nbsp;</div>
            </div>
        </div>
    </div>
</div>