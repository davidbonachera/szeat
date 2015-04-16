<?php require_once('class/dblogin.inc.php'); ?>
<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <link rel="stylesheet" type="text/css" href="css/dd.css"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!--
    <script type="text/javascript" src="js/jquery.dd.min.js"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    -->
    <script src="js/chosen/chosen.jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="js/chosen/chosen.css" />
    <script type="text/javascript" charset="utf-8">
    $(document).ready(function(e) {
    	try {
			$(".styled").chosen(); 
			/*
    		$(".home-fotm-box select#area").msDropDown();
			$(".home-fotm-box select#building").msDropDown();
			$(".home-fotm-box select#cuisines").msDropDown();
			*/
    	} catch(e) {
    		// alert(e.message);
    	}
    });
	$(function(){
		$("select#area").change(function(){
			$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
				var Options = '';
				for (var i = 0; i < j.length; i++) {
					Options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
				}
				$("select#building").html(Options);
				$("select#building").trigger("liszt:updated");
			});
		});
		$('#searchForm').submit(function() {
			$(".HomePageError").hide();
			if ($("#area").val()=="") {
				$(".HomePageError").html("Please select an area and building.");
				$(".HomePageError").fadeIn("slow");
				return false;
			}
			if ($("#building").val()=="") {
				$(".HomePageError").html("Please select an area and building.");
				$(".HomePageError").fadeIn("slow");
				return false;
			}
		});
	});
	</script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="contet-title">Order your takeaway online</div>
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
                            	<input type="submit" value="Find me a takeaway" />
                            </div>
                        </form>
                        
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="HomePageError">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once('includes/footer.php'); ?>
    
</body>
</html>
