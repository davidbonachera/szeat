<?php require_once('class/config.inc.php'); ?>
<?php require_once('global/functions.php'); ?>
<?php 
if (isset($_SESSION['user'])) unset($_SESSION['user']);
header("Location: index.php");
exit;
?>