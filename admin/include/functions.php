<?php
###################
## Check Session ##
###################
function is_session() {
	if (!checkFeild($_SESSION['aid'])){
		header('Location: index.php?msg=3');
		exit();
	}
}

####################
## Login Function ##
####################
function login() {
	global $db;
	$username = stripslashes($_POST["username"]);
	$password = md5($_POST["password"]);
	
	if ($username!='' && $password!='') {
		$login 	= "SELECT * FROM admin WHERE username='$username' AND password='$password' AND status=1";
		$rows 	= $db->query_first($login);
		if($db->affected_rows > 0){
			$_SESSION['aid'] = $rows['id'];			
			header('Location: dashboard.php');
			exit();
		} else {
			$err['error'] = "username or password is incorrect";
			$err['class'] = "msg_error";
		}
	} else {
		$err['error'] = "Enter your username and password.";
		$err['class'] = "msg_error";
	}
	return $err;
}

#############################
## DASHBOARD Stats Counter ##
#############################
function counter($table,$status=NULL) {
	global $db;
	$query = "SELECT * FROM $table";
	
	if ($status == "0" || $status == "1" || $status == "2") {
		$sq = " WHERE status=$status";
	} elseif ($status == "3") {
		$sq = " WHERE sent=0 AND status=1";
	} else {
		$sq = NULL;
	}

	$row = $db->query($query.$sq);
	$count = $db->affected_rows;
	return $count;
}

function recordsCounter($table,$column,$id) {
	global $db;
	$row = $db->query("SELECT * FROM `$table` WHERE `$column`='$id'");
	if ($db->affected_rows > 0) {
		$count = $db->affected_rows;
	}else{
		$count = 0;
	}
	return $count;
}

function itemsNotPaid($id,$start=NULL,$end=NULL) {
	global $db;
	if ($start!=NULL) {
		if ($end==NULL) $end = date("m/d/Y");
		
		$startDate 	= date("Y-m-d",strtotime($start));
		$endDate 	= date("Y-m-d",strtotime($end));
		
		$query = $db->query("SELECT id FROM orders WHERE restaurant_id='$id' AND paid=0 AND DATE(date) BETWEEN '$startDate' AND '$endDate'");
	} else {
		$query = $db->query("SELECT id FROM orders WHERE restaurant_id='$id' AND paid=0");
	}
	
	if ($db->affected_rows > 0) {
		while($r=$db->fetch_array($query)) {
			$idsArray[] = $r['id'];
		}
		$idsImploded = implode(",", $idsArray);
		
		$row   = $db->query_first("SELECT COUNT(*) AS total FROM order_items WHERE order_id IN ($idsImploded)");
		$count = $row['total'];
	}else{
		$count = 0;
	}
	return $count;
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

function AdminDetails($id,$val) {
	global $db;
	if($val=="name") {
		$q = $db->query_first("SELECT fname,lname FROM admin WHERE id='$id'");
		if ($db->affected_rows > 0) {
			$value = $q['fname']." ".$q['lname'];
		} else {
			$value = "N/A";
		}
	} else {
		$q = $db->query_first("SELECT $val FROM users WHERE id='$id'");
		if ($db->affected_rows > 0) {
			$value = $q[$val];
		} else {
			$value = "N/A";
		}
	}
	return $value;
}

function getEnumValues($table, $field) {
	global $db;
    $enum_array = array();
    $query = 'SHOW COLUMNS FROM `' . $table . '` LIKE "' . $field . '"';
    $row = $db->query_first($query);

    preg_match_all('/\'(.*?)\'/', $row['Type'], $enum_array);	

    if(!empty($enum_array[1])) {
        foreach($enum_array[1] as $mkey => $mval) $enum_fields[$mkey+1] = $mval;
        return $enum_fields;
    } else {
		return array();
	}
	
}

function country($id) {
	global $db;
	$c = $db->query_first("SELECT name FROM countries WHERE id=$id");
	if ($db->affected_rows > 0) {
		$value = $c['name'];
	} else {
		$value = "N/A";
	}
	return $value;
}
function countryFlag($id) {
	global $db;
	$c = $db->query_first("SELECT name, iso2 FROM countries WHERE id=$id");
	if ($db->affected_rows > 0) {
		$value = "<img src='../flags/{$c['iso2']}.gif' title='{$c['name']}' />";
	} else {
		$value = $c['name'];
	}
	return $value;
}


function Array2Dto1D($fc) {
	foreach ($fc as $key1 => $arr) {
		foreach ($arr as $key2 => $num) {
			$result[$key2][$key1] = $num;
		}
	}
	return $result;
}

function getData($table,$column,$id) {
	global $db;
	$c = $db->query_first("SELECT $column FROM $table WHERE id=$id");
	if ($db->affected_rows > 0) {
		$val = $c[$column];
	} else {
		$val = NULL;
	}
	return $val;
}

function countImages($id) {
	global $db;
	$c = $db->query_first("SELECT count(*) AS CNT FROM resources WHERE gallery_id=$id");
	return $c['CNT'];
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

function sendEmail($fromName, $fromEmail, $toEmail, $subject, $emailBody) {
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
		$mail->AddAddress($toEmail);
		
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

?>