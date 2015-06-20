<?php isLoggedIn(); ?>
<?php
$currencySymbol = _priceSymbol;
if ($_POST) {
	if (checkFeild($_POST['name'])) {
		if (checkFeild($_POST['phone'])) {
			if (checkFeild($_POST['area'])) {
				if (checkFeild($_POST['building'])) {
					if (checkFeild($_POST['apartment'])) {
						if (checkFeild($_POST['price'])) {

							$data['name'] 			= $db->escape($_POST['name']);
							$data['phone'] 			= $db->escape($_POST['phone']);
							
							if ($_POST['area_id']!='Select') $data['area_id'] = $db->escape($_POST['area']);
							if ($_POST['building']!='Select') $data['building_id'] = $db->escape($_POST['building']);
							
							$data['apartment'] 		= $db->escape($_POST['apartment']);
							
							if ($db->query_update("users",$data,"id={$_SESSION['user']['id']}")) {
								
								unset($data);
											   $data['user_id'] 		= $_SESSION['user']['id'];
								$oRestaurant = $data['restaurant_id'] 	= $_SESSION['user']['restaurant']['id'];
								$oPrice 	 = $data['price'] 			= $db->escape($_POST['price']);
								$oNotes 	 = $data['notes'] 			= $db->escape($_POST['notes']);
								$data['status'] 		= 1;
								
								$orderID = $db->query_insert("orders",$data);
								
								if ($db->affected_rows > 0) {
									unset($data);
									
									$itemsTable = "<table>";

									foreach ($_SESSION['user']['items'] as $key=>$item) {
										
										$data2['user_id'] 			= $_SESSION['user']['id'];
										$data2['restaurant_id'] 	= $_SESSION['user']['restaurant']['id'];
										$data2['order_id'] 			= $orderID;
										$data2['menu_item_id'] 		= $item['id'];
										$data2['menu_item_size'] 	= $item['size'];
										$data2['quantity'] 			= $item['quantity'];
										$data2['status'] 			= 1;
										
										$order_item = $db->query_insert("order_items",$data2);
									}
									
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
									
									if (isset($_SESSION['user']['items'])) unset($_SESSION['user']['items']);
									
									$siteTitle 	= _title;
									$fromEmail 	= _email;
									$toEmail 	= getData("users","email",$_SESSION['user']['id']);
									$toName 	= getData("users","name",$_SESSION['user']['id']);
									$resName	= getData("restaurants","name",$oRestaurant);
									
									$template	= $db->query_first("SELECT * FROM email_templates WHERE name='order_placed' LIMIT 1");
					
									$from_name	= $template['from_name'];
									$from_email	= $template['from_email'];
									
									$mSubject = $template['subject'];
									$mSubject = str_replace("#NAME#", 			$toName, 	$mSubject);
									$mSubject = str_replace("#RESTAURANT#", 	$resName, 	$mSubject);
									$mSubject = str_replace("#ORDERNO#", 		$orderID, 	$mSubject);
									$mSubject = str_replace("#ITEMDETAILS#", 	$itemsTable,$mSubject);
									$mSubject = str_replace("#TOTALPRICE#", 	$oPrice, 	$mSubject);
									$mSubject = str_replace("#NOTES#", 			$oNotes, 	$mSubject);

									$mBody = html_entity_decode($template['body']);
									$mBody = str_replace("#NAME#", 			$toName, 	$mBody);
									$mBody = str_replace("#RESTAURANT#", 	$resName, 	$mBody);
									$mBody = str_replace("#ORDERNO#", 		$orderID, 	$mBody);
									$mBody = str_replace("#ITEMDETAILS#", 	$itemsTable,$mBody);
									$mBody = str_replace("#NOTES#", 		$oNotes, 	$mBody);
									$mBody = str_replace("#TOTALPRICE#", 	number_format($oPrice,2), $mBody);

									sendEmail($from_name, $from_email, $toEmail, $mSubject, $mBody);
									
									$_SESSION['error'] = false;
									$_SESSION['msg']   = 'Your order has been placed successfully.';
								} else {
									$_SESSION['error'] = true;
									$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
								}
							} else {
								$_SESSION['error'] = true;
								$_SESSION['msg']   = 'Oops, something went wrong. please try again later.';
							}
						} else {
							$_SESSION['error'] = true;
							$_SESSION['msg']   = 'Order price must be greated than 0.';
							header("Location: index.php?page=order-details");
							exit;
						}
					} else {
						$_SESSION['error'] = true;
						$_SESSION['msg']   = 'Please enter your block/apartment.';
						header("Location: index.php?page=order-details");
						exit;
					}
				} else {
					$_SESSION['error'] = true;
					$_SESSION['msg']   = 'Please select your building.';
					header("Location: index.php?page=order-details");
					exit;
				}
			} else {
				$_SESSION['error'] = true;
				$_SESSION['msg']   = 'Please select your area.';
				header("Location: index.php?page=order-details");
				exit;
			}
		} else {
			$_SESSION['error'] = true;
			$_SESSION['msg']   = 'Please enter your phone number.';
			header("Location: index.php?page=order-details");
			exit;
		}
	} else {
		$_SESSION['error'] = true;
		$_SESSION['msg']   = 'Please enter your phone name.';
		header("Location: index.php?page=order-details");
		exit;
	}
} elseif (isset($_GET['order'])) {
	
	if (isset($_GET['reorder'])) {
		$orderID 	= $db->escape($_GET['order']);
		$order 		= $db->query_first("SELECT * FROM orders WHERE id=$orderID");
		$items 		= $db->query("SELECT * FROM order_items  WHERE order_id=$orderID");

		$_SESSION['user']['restaurant']['name'] = getData("restaurants","name",$order['restaurant_id']);
		$_SESSION['user']['restaurant']['id'] 	= $order['restaurant_id'];
		
		if ($db->affected_rows > 0) {
			unset($_SESSION['user']['items']);
			while($ir=$db->fetch_array($items)) {		
				$key = $_SESSION['user']['item_key'] = isset($_SESSION['user']['item_key']) ? $_SESSION['user']['item_key']+1:0;
				$_SESSION['user']['items'][$key]['id'] 	 		= $ir['menu_item_id'];
				$_SESSION['user']['items'][$key]['size'] 		= $ir['menu_item_size'];
				$_SESSION['user']['items'][$key]['quantity'] 	= $ir['quantity'];
				$_SESSION['user']['items'] = array_values($_SESSION['user']['items']);
			}
		}
		$restaurant = urlText(getData("restaurants","name",$order['restaurant_id']));
		header("Location: index.php?page=menu&restaurant={$restaurant}&id={$order['restaurant_id']}");
		exit;
	} else {
		$orderID = $db->escape($_GET['order']);
	}
} else {
	header("Location: account-details.php?tab=my-recent-orders");
	exit;
}