<?php  


//$one_time_flag=0;
//print_r($_REQUEST);die;
$layerarray = array();
include('class/config.inc.php');
			if(!empty($_REQUEST['layerandattribute'])&& isset($_REQUEST['layerandattribute']))	 //if start here
			{				
	$str=$_REQUEST['layerandattribute'];
	
	$str= explode('*',$str);
	
	//echo "<pre>";print_r($str);die;
	
	
	array_shift($str);
	$layerarray = array();
	for($i=0;$i<count($str);$i++){
		
	$data =  explode(',',$str[$i]);
	
	$layerarray[$i]['id']=$data[0];
		unset($data[0]);
		if(!empty($data) && isset($data))
		{
		$occurences = array_count_values($data);
		//print_r($occurences); die;
		$layerarray[$i]['attributes']=$occurences;
		}
	
	//print_r($data); die;
	if(empty($data) )
	{
		unset($layerarray[$i]);
		
	}
		
		
		
}
/*
for($i=0;$i<count($str);$i++){
	$data =  explode(',',$str[$i]);
	//echo "<pre>";print_r($data);
	$key = $data[0];
	unset($data[0]);
	$arr[$key] = $data;
}

*/

	//print_r($layerarray); die;

	
	//echo "<pre>";print_r($nwearr); die;
	
unset($_GET['layerandattribute']);


			} // if end here
			
if (isset($_GET['add_item'])) { //echo  "if". "<pre>";print_r($_GET); die;
   
	/*if (foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id') && foundValueInArray($_SESSION['user']['items'], $_GET['size'], 'size')) { 
		$key = foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id')-1;
		
		$_SESSION['user']['items'][$key]['quantity'] = $_SESSION['user']['items'][$key]['quantity']+1;
		
		
		
	} else {//	echo "else". "<pre>";print_r($_GET); die; */
		$key = $_SESSION['user']['item_key'] = isset($_SESSION['user']['item_key']) ? $_SESSION['user']['item_key']+1:0;
		$_SESSION['user']['items'][$key]['id'] 	 = $_GET['add_item'];
		$_SESSION['user']['items'][$key]['size'] = $_GET['size'];
		$_SESSION['user']['items'][$key]['layers'] =$layerarray;
		$_SESSION['user']['items'][$key]['quantity'] = 1;
		$_SESSION['user']['items'] = array_values($_SESSION['user']['items']);

		
	/*   }   */
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

print_r('success making'); die;