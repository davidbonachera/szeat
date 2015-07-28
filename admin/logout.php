<?php require_once("../class/config.inc.php"); ?>
<?php include("include/functions.php"); ?>
<?php
unset($_SESSION['aid']);
header("Location: index.php?msg=2");
exit();
?>