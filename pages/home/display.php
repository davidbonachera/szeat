<div class="container">

    <div class="row">

        <div id="homesub" class="text-center page-header">
            <h2>Order your food delivery online</h2>
        </div>

    </div>

    <div class="row">
            
        <div id="startbox" class="col-xs-12">

            <form method="get" action="search.php" id="searchForm">

                <div class="form-group">
                    
                    <div class="col-md-3">
                        <label class="control-label">Where do you live?</label>                
                        <select name="area" id="area" class="chosen-select form-control">
                            <option value="">Select Area</option>
                            <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($areas)) { ?>
                            <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $areas loop ?>
                        </select>
                    </div>
                
                    <div class="col-md-3">
                        <label class="control-label">What building?</label>  
                        <select name="building" id="building" class="chosen-select form-control">
                            <option value="">Select Building</option>
                            <?php $buildings = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($buildings)) { ?>
                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $buildings loop ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label">What do you fancy?</label>  
                        <select name="cuisines" id="cuisines" class="chosen-select form-control">
                            <option value="">Show Everything</option>
                            <?php $cuisines = $db->query("SELECT * FROM cuisines WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $areas loop ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input id="homesubmit" class="btn btn-yellow" type="submit" value="Find me a food delivery!">

                    </div>

                    <p class="HomePageError">&nbsp;</p>
                    
                </div>
            </form>
        </div>
    </div>
    
</div>
