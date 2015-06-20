<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once("class/class.phpmailer.php"); ?>
<?php require_once('global/functions.php'); ?>
<?php 

$link           = '#';
$check['name']  = 'TEST';
$check['email'] = 'zainsohail88@gmail.com';

$template	  = $db->query_first("SELECT * FROM email_templates WHERE name='reset_password' LIMIT 1");
$from_name	  = $template['from_name'];
$from_email	  = $template['from_email'];
$subject 	  = $template['subject'];
$messageBody  = html_entity_decode($template['body']);
$messageBody  = str_replace("#NAME#", 	$check['name'], 	$messageBody);
$messageBody  = str_replace("#LINK#", 	$link, 				$messageBody);
$debug        = true;

echo "<h2>SMTP Debug Log:</h2>";
if (sendEmail($from_name, $from_email, $check['email'], $subject, $messageBody, $debug)) {
	echo $_SESSION['msg']   = '<h1 style="color:green">Success!</h1>';
} else {
	echo $_SESSION['msg']   = '<h1 style="color:red">Failure!</h1>';
}
