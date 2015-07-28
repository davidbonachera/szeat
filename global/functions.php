<?php
if (isset($_GET['add_item'])) {
	if (foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id') && foundValueInArray($_SESSION['user']['items'], $_GET['size'], 'size')) {
		$key = foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id')-1;
		$_SESSION['user']['items'][$key]['quantity'] = $_SESSION['user']['items'][$key]['quantity']+1;
		
		$restaurant = urlText($_GET['restaurant']);
		if ($orderPage) {
			header("Location: index.php?page=order-details");
			exit;
		} else {
			header("Location: index.php?page=menu&restaurant={$restaurant}&id={$_GET['id']}");
			exit;
		}
		
	} else {		
		$key = $_SESSION['user']['item_key'] = isset($_SESSION['user']['item_key']) ? $_SESSION['user']['item_key']+1:0;
		$_SESSION['user']['items'][$key]['id'] 	 = $_GET['add_item'];
		$_SESSION['user']['items'][$key]['size'] = $_GET['size'];
		$_SESSION['user']['items'][$key]['quantity'] = 1;
		$_SESSION['user']['items'] = array_values($_SESSION['user']['items']);

		$restaurant = urlText($_GET['restaurant']);
		if ($orderPage) {
			header("Location: index.php?page=order-details");
			exit;
		} else {
			$restaurantID = $db->escape($_GET['id']);
			header("Location: index.php?page=menu&restaurant={$restaurant}&id=".$restaurantID);
			exit;
		}
	}
} elseif (isset($_GET['remove_item'])) {
	if (foundValueInArray($_SESSION['user']['items'], $_GET['remove_item'], 'id') && foundValueInArray($_SESSION['user']['items'], $_GET['size'], 'size')) {
		$key = foundValueInArray($_SESSION['user']['items'], $_GET['remove_item'], 'id')-1;
		unset($_SESSION['user']['items'][$key]);
		$_SESSION['user']['items'] = array_values($_SESSION['user']['items']);
		
		$restaurant = urlText($_GET['restaurant']);
		if ($orderPage) {
			header("Location: index.php?page=order-details");
			exit;
		} else {
			$restaurantID = $db->escape($_GET['id']);
			header("Location: index.php?page=menu&restaurant={$restaurant}&id=".$restaurantID);
			exit;
		}
	}
}

function isLoggedIn() {
	if (isset($_SESSION['user']['id'])) {
		if (checkFeild($_SESSION['user']['id'])) {
			return true;
		} else {
			header("Location: index.php");
			exit;
		}
	} else {
		header("Location: index.php");
		exit;
	}
}
#######################################
####### Email Sending Function ########
#######################################

function readTemplateFile($FileName) {
		$fp = fopen($FileName,"r") or exit("Unable to open File ".$FileName);
		$str = "";
		while(!feof($fp)) {
			$str .= fread($fp,1024);
		}	
		return $str;
}

function sendEmail($fromName, $fromEmail, $toEmail, $subject, $emailBody, $debug=false) {
	global $db;
	
	$smtp 			= $db->query_first("SELECT * FROM smtp WHERE id=1");
	$mail 			= new PHPMailer(true);
	$mail->CharSet 	= 'utf-8';
	
	try {
		
		$mail->IsSMTP();
		$mail->SMTPDebug  	= $debug===true ? 2:0;
		$mail->Debugoutput 	= 'html';
		$mail->Host       	= $smtp['server'];
		$mail->Port       	= $smtp['port'];
		
		if ($smtp['ssl']==1) {
			$mail->SMTPSecure = "ssl";
		}
		
		$mail->SMTPAuth   = true;
		$mail->Username   = $smtp['username'];
		$mail->Password   = $smtp['password'];
		$mail->From       = $fromEmail;
		$mail->FromName   = $fromName;		
		$mail->AddAddress($toEmail);
		$mail->Subject 	= $subject;
		$mail->WordWrap = 80;
		//echo print_r($mail);die;
		//echo $mail->MsgHTML($emailBody, dirname(__FILE__), true); die;
		$mail->MsgHTML($emailBody, dirname(__FILE__), true); //Create message bodies and embed images
		
		if ($mail->Send()) {
			//echo "yes";
			return true;
		}else{
			//echo "no";
			return false;
		}
		//die;
	} catch (phpmailerException $e) {
		if ($debug===true) { 
			echo '<pre>';
			echo "<h2>SMTP Settings:</h2>";
			print_r($smtp);
			echo "<h2>Exception Details:</h2>";
			print_r($e);
			echo "<h2>Mailer Details:</h2>";
			print_r($mail);
			exit;
		}
		return false;
	}
}

function sendEmailBCC($fromName, $fromEmail, $toBCC, $subject, $emailBody) {
	global $db;
	
	$smtp 			= $db->query_first("SELECT * FROM smtp WHERE id=1");
	$mail 			= new PHPMailer(true);
	$mail->CharSet 	= 'utf-8';
	
	try {
		
		$mail->IsSMTP();
		$mail->SMTPDebug  	= 0;
		$mail->Debugoutput 	= 'html';
		$mail->Host       	= $smtp['server'];
		$mail->Port       	= $smtp['port'];
		
		if ($smtp['ssl']==1) {
			$mail->SMTPSecure = "ssl";
		}
		
		$mail->SMTPAuth   = true;
		$mail->Username   = $smtp['username'];
		$mail->Password   = $smtp['password'];
		$mail->From       = $fromEmail;
		$mail->FromName   = $fromName;		
		
		$emails = explode(',',$toBCC);
		foreach ($emails as $k=>$id) {
			$mail->AddBCC(getSubscriberEmail($id));
		}
		
		$mail->Subject 	= $subject;
		$mail->WordWrap = 80;
		$mail->MsgHTML($emailBody, dirname(__FILE__), true); //Create message bodies and embed images
		
		if ($mail->Send()) {
			return true;
		}else{
			return false;
		}
		
	} catch (phpmailerException $e) {
		return false;
	}
}


#################
## Check Field ##
#################
function checkFeild($a) {
	if (trim($a) == "" || $a == NULL || empty($a)) {
		return false;
	} else {
		return true;
	}
}

#################
## Valid Email ##
#################
function validEmail($email) {
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex) {
	   $isValid = false;
	} else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
         // local part length exceeded
         $isValid = false;
      } elseif ($domainLen < 1 || $domainLen > 255) {
         // domain part length exceeded
         $isValid = false;
      } elseif ($local[0] == '.' || $local[$localLen-1] == '.') {
         // local part starts or ends with '.'
         $isValid = false;
      } elseif (preg_match('/\\.\\./', $local)) {
         // local part has two consecutive dots
         $isValid = false;
      } elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
         // character not valid in domain part
         $isValid = false;
      } elseif (preg_match('/\\.\\./', $domain)) {
         // domain part has two consecutive dots
         $isValid = false;
      } elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            $isValid = false;
         }
      }
   }
   return $isValid;
}

function recordAvailable($table,$col,$val) {
	global $db;
	$c = $db->query_first("SELECT id FROM $table WHERE $col='$val'");
	if ($db->affected_rows > 0) {
		$availble = false;
	} else {
		$availble = true;
	}
	return $availble;
}

function __($string) {
	if (strstr(strtolower($string),'faq') || strstr(strtolower($string),'t/c')) echo $string;
	else echo ucwords(strtolower($string));
}

function getData($table,$column,$id) {
	global $db;
	$c = $db->query_first("SELECT $column FROM $table WHERE id=$id");
	if ($db->affected_rows > 0) {
		$val = $c[$column];
	} else {
		$val = "N/A";
	}
	return $val;
}

function countCuisines($id) {
	global $db;
	$id = $db->escape($id);
	
	$area 		= $db->escape($_GET['area']);
	$building 	= $db->escape($_GET['building']);
	$cuisines 	= $db->escape($_GET['cuisines']);
	
	if (checkFeild($area)) {;
		$where = "WHERE ra.area_id=$area";
	}
	if (checkFeild($building)) {
		$where = isset($where) ? $where." AND rb.building_id=$building":"WHERE rb.building_id=$building";
	}
	
	$where = isset($where) ? $where." AND r.status=1":"WHERE r.status=1";

	if (!checkFeild($id) || $id=='all') {
		$r = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.area_id, rb.building_id, (SELECT GROUP_CONCAT(cuisine_id separator ', ') AS cuisines FROM restaurants_cuisines AS rc WHERE rc.restaurant_id=r.id) AS cuisines FROM restaurants AS r 
								LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
								LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id
								$where GROUP BY id");
	} else {
		$r = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.area_id, rb.building_id, (SELECT GROUP_CONCAT(cuisine_id separator ', ') AS cuisines FROM restaurants_cuisines AS rc WHERE rc.restaurant_id=r.id) AS cuisines FROM restaurants AS r 
								LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
								LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id
								$where AND $id IN (SELECT cuisine_id FROM restaurants_cuisines AS rc WHERE rc.restaurant_id=r.id) GROUP BY id");
	}
	if ($db->affected_rows > 0) {
		$total = $db->affected_rows;
	} else {
		$total = 0;
	}
	return $total;
}

function array_orderby() {
	$args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field) {
		if (is_string($field)) {
			$tmp = array();
			foreach ($data as $key => $row)
				$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array('array_multisort', $args);
	return array_pop($args);
}
function dayDate($day) {
	$dayArr = array(
				0 => 'MONDAY',
				1 => 'TUESDAY',
				2 => 'WEDNESDAY',
				3 => 'THURSDAY',
				4 => 'FRIDAY',
				5 => 'SATURDAY',
				6 => 'SUNDAY'
			);
	$monday = mktime(0, 0, 0, date('m'), date('d')+(1-date('w')), date('Y'));

	$n 		= array_search("$day",$dayArr);
	$date 	= date('m-d-Y', $monday+$n*60*60*24);
	return $date;
}

function ratings($restaurant_id) {
	global $db;
	$qr = $db->query_first("SELECT AVG(ratings) AS rating FROM `ratings` WHERE `restaurant_id`=$restaurant_id AND status=1");
	if ($db->affected_rows > 0) {
		$rating = round($qr['rating']);
	} else {
		$rating = 0;
	}
	$qc = $db->query_first("SELECT COUNT(*) AS total FROM `ratings` WHERE `restaurant_id`=$restaurant_id AND status=1");
	if ($db->affected_rows > 0) {
		$count = $qc['total'];
	} else {
		$count = 0;
	}
	$data = array(
				"rating" => $rating, 
				"count"  => $count
			);
	return $data;
}
function checkExists($table,$column,$val) {
	global $db;
	$c = $db->query_first("SELECT * FROM `$table` WHERE `$column`='$val'");
	if ($db->affected_rows > 0) {
		$exists = true;
	} else {
		$exists = false;
	}
	return $exists;
}

function foundValueInArray($array, $value, $key) {
	$found = false;
	for($i=0; $i < sizeof($array); $i++) {
		if ($array[$i][$key] == $value) {
			$found = $i+1;
		}
	}
	if ($found) {
		return $found;
	} else {
		return false;
	}
}

function urlText($string) {
	if (checkFeild($string)) {
		$encoded = htmlentities(strtolower(str_replace(array('&',' '),array('_','+'),$string)));
	}else{
		$encoded = "";
	}
	return $encoded;
}

function deliveryHours($restaurantID,$today=false) {
	global $db;
	$dhq = $db->query("SELECT * FROM delivery_times WHERE restaurant_id=$restaurantID AND status=1");
	if ($db->affected_rows > 0) { 
		while($dr=$db->fetch_array($dhq)) {
			isset($i) ? $i++:$i=0;
			$del_hours[$i] = $dr;
			$del_hours[$i]['date'] = dayDate($dr['day']);
		}
		$del_hours = array_orderby($del_hours, 'date', SORT_ASC);
	} else {
		$del_hours = array();
	}
	if ($today==true) {
		foreach ($del_hours as $today_hours) {
			if (strtolower($today_hours['day'])==strtolower(date("l"))) {
				$hours['day'] 		= $today_hours['day'];
				$hours['start'] 	= date("h:ia",strtotime($today_hours['start']));
				$hours['end'] 		= date("h:ia",strtotime($today_hours['end']));
				$hours['status'] 	= $today_hours['status'];
			}
		}
		$value = $hours;
	} else {
		foreach ($del_hours as $key=>$today_hours) {
			$hours[$key]['day'] 	= $today_hours['day'];
			$hours[$key]['start'] 	= date("h:ia",strtotime($today_hours['start']));
			$hours[$key]['end'] 	= date("h:ia",strtotime($today_hours['end']));
			$hours[$key]['status'] 	= $today_hours['status'];
		}
		$value = $hours;
	}
	return $value;
}
?>