<?php $xml = simplexml_load_file("pages/home/content.xml"); ?>
<div class="container">

    <div class="row">

        <div id="homesub" class="text-center page-header">
            <h2><?php echo ($xml->$lang->tagline == "" ? $xml->en->tagline : $xml->$lang->tagline); ?></h2>
        </div>

    </div>

    <div class="row">
            
        <div id="startbox" class="col-xs-12">

            <form method="get" action="search.php" id="searchForm">

                <div class="form-group">
                    
                    <div class="col-md-3">
                        <label class="control-label"><?php echo ($xml->$lang->where == "" ? $xml->en->where : $xml->$lang->where); ?></label>
                        <select name="area" id="area" class="chosen-select form-control">
                            <option value=""><?php echo ($xml->$lang->selectArea == "" ? $xml->en->selectArea : $xml->$lang->selectArea); ?></option>
                            <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($areas)) { ?>
                            <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $areas loop ?>
                        </select>
                    </div>
                
                    <div class="col-md-3">
                        <label class="control-label"><?php echo ($xml->$lang->building == "" ? $xml->en->building : $xml->$lang->building); ?></label>
                        <select name="building" id="building" class="chosen-select form-control">
                            <option value=""><?php echo ($xml->$lang->selectBuilding == "" ? $xml->en->selectBuilding : $xml->$lang->selectBuilding); ?></option>
                            <?php $buildings = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($buildings)) { ?>
                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $buildings loop ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label"><?php echo ($xml->$lang->foodtype == "" ? $xml->en->foodtype : $xml->$lang->foodtype); ?></label>
                        <select name="cuisines" id="cuisines" class="chosen-select form-control">
                            <option value=""><?php echo ($xml->$lang->showAll == "" ? $xml->en->showAll : $xml->$lang->showAll); ?></option>
                            <?php $cuisines = $db->query("SELECT * FROM cuisines WHERE status=1 ORDER BY title ASC"); ?>
                            <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                            <?php } // while $areas loop ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input id="homesubmit" class="btn btn-yellow" type="submit" value="<?php echo ($xml->$lang->search == "" ? $xml->en->search : $xml->$lang->search); ?>">

                    </div>

                    <p class="HomePageError">&nbsp;</p>
                    
                </div>
            </form>
        </div>
    </div>
    
</div>
