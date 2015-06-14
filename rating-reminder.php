<?php require_once('class/config.inc.php'); ?>
<?php require_once('class/class.phpmailer.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php
$currencySymbol = _priceSymbol;

$orders = $db->query("SELECT *, HOUR(date) FROM `orders` 
					  WHERE 
					  	(date > DATE_SUB(NOW(), INTERVAL 23 HOUR))
					  	AND id NOT IN (SELECT order_id FROM ratings) 
						AND status=1 
						AND reminder=0
					  ORDER BY HOUR(date)");

if ($db->affected_rows > 0) {
	while($order=$db->fetch_array($orders)) {
		
		$siteTitle 	= _title;
		$fromEmail 	= _email;
		$toEmail 	= getData("users","email",$order['user_id']);
		$toName 	= getData("users","name",$order['user_id']);
		$resName	= getData("restaurants","name",$order['restaurant_id']);
		$orderID	= $order['id'];
		$oPrice		= $order['price'];
		$oNotes		= $order['notes'];
		$linkToRate	= $config['siteURL']."index.php?page=rate-takeaway&order=".$order['id'];
		
		$itemsTable = "<table>";
		
		$items = $db->query("SELECT * FROM order_items WHERE order_id='$orderID'");
		while ($item = $db->fetch_array($items)) {
			$menuItem = $db->query_first("SELECT * FROM menu_items WHERE id={$item['menu_item_id']}");
			if ($db->affected_rows > 0) {
				if ($item['menu_item_size'] > 0) {
					$menuSize = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['menu_item_size']}");
					$itemValue = $menuSize['value'];
					$itemPrice = number_format($menuSize['price']*$item['quantity'],2);
				} else {
					$itemValue = $menuItem['value'];
					$itemPrice = number_format($menuItem['price']*$item['quantity'],2);
				}
				$itemsTable .= "<tr>
									<td>{$item['quantity']}x - {$menuItem['item_number']}</td>
									<td>{$menuItem['name']} $itemValue</td>
									<td>$itemPrice</td>
								 </tr>
								";
			}
			
		} // end foreach $items loop
		$itemsTable .= "</table>";

		$template	= $db->query_first("SELECT * FROM email_templates WHERE name='rating_reminder' LIMIT 1");
					
		$from_name	= $template['from_name'];
		$from_email	= $template['from_email'];
		
		$mSubject = $template['subject'];
		$mSubject = str_replace("#NAME#", 			$toName, 	$mSubject);
		$mSubject = str_replace("#RESTAURANT#", 	$resName, 	$mSubject);
		$mSubject = str_replace("#ORDERNO#", 		$orderID, 	$mSubject);
		$mSubject = str_replace("#ITEMDETAILS#", 	$itemsTable,$mSubject);
		$mSubject = str_replace("#NOTES#", 			$oNotes, 	$mSubject);
		$mSubject = str_replace("#TOTALPRICE#", 	number_format($oPrice,2), $mSubject);

		$mBody = html_entity_decode($template['body']);
		$mBody = str_replace("#NAME#", 			$toName, 	$mBody);
		$mBody = str_replace("#RESTAURANT#", 	$resName, 	$mBody);
		$mBody = str_replace("#ORDERNO#", 		$orderID, 	$mBody);
		$mBody = str_replace("#ITEMDETAILS#", 	$itemsTable,$mBody);
		$mBody = str_replace("#TOTALPRICE#", 	$oPrice, 	$mBody);
		$mBody = str_replace("#NOTES#", 		$oNotes, 	$mBody);
		$mBody = str_replace("#LINK#", 			$linkToRate,$mBody);

		$data['reminder'] 	= 1;
		$reminderSent 		= $db->query_update("orders",$data,"id={$order['id']}");
		
		sendEmail($from_name, $from_email, $toEmail, $mSubject, $mBody);
	}
}
?>