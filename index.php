<?php
    require_once('class/dblogin.inc.php');
    require_once('class/config.inc.php');
    require_once('global/global_vars.php');
    require_once("class/Pagination.class.php");
    require_once("class/class.phpmailer.php");
    require_once('includes/functions.php');
    require_once('global/global_meta.php');
    if(file_exists('pages/'.$page.'/local.css')) {
        echo '<link rel="stylesheet" type="text/css" href="pages/'.$page.'/local.css"/>';
    }
    require_once('global/header.php');
    if(file_exists('pages/'.$page.'/display.php')) {
        if(file_exists('pages/'.$page.'/local_vars.php')) {
            require('pages/'.$page.'/local_vars.php');
        }
        require 'pages/'.$page.'/display.php';
    } else {
        require 'pages/home/display.php';
    }
    require('global/footer.php');
?>


<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/static.getclicky.com.js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100728789); }catch(e){}</script>
<?php if (isset($_SESSION['error'])) unset($_SESSION['error'],$_SESSION['msg']); ?>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/custom-form-elements.js"></script>
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<?php if(file_exists('pages/'.$page.'/local.js')) {
        echo '<script type="text/javascript" src="pages/'.$page.'/local.js"></script>';
    }
?>
<script type="text/javascript" src="js/custom.js"></script>
<?php
    if ($lang=='cn') {
        echo '<script type="text/javascript" src="js/custom_cn.js"></script>';
    } else {
        echo '<script type="text/javascript" src="js/custom_en.js"></script>';
    }
?>

</body>
</html>
