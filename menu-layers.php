<?php  


$one_time_flag=0;
//print_r($_POST['menu_name']);die;
//session_start();
//session_destroy();

include('class/config.inc.php');


 
  
  
				//"page=" + page + "&restaurant=" + resturante + "&add_item=" + add_item + "&size=" + size + "&id=" + id  ,
/*
if (isset($_GET['add_item'])) {  // echo "<pre>";print_r($_GET); die;
   
	if (foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id') && foundValueInArray($_SESSION['user']['items'], $_GET['size'], 'size')) { 
		$key = foundValueInArray($_SESSION['user']['items'], $_GET['add_item'], 'id')-1;
		
		$_SESSION['user']['items'][$key]['quantity'] = $_SESSION['user']['items'][$key]['quantity']+1;
		
		
		
	} else {//	echo "here". "<pre>";print_r($_GET); die;
		$key = $_SESSION['user']['item_key'] = isset($_SESSION['user']['item_key']) ? $_SESSION['user']['item_key']+1:0;
		$_SESSION['user']['items'][$key]['id'] 	 = $_GET['add_item'];
		$_SESSION['user']['items'][$key]['size'] = $_GET['size'];
		$_SESSION['user']['items'][$key]['quantity'] = 1;
		$_SESSION['user']['items'] = array_values($_SESSION['user']['items']);

		
	}
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

*/
$item_name ;
 $sqlfirst="SELECT * FROM menu_items WHERE id='".$_GET['add_item']." '  "; 
$resultfirst=mysql_query($sqlfirst);
while($rowfirst = mysql_fetch_assoc($resultfirst)) {  
$item_name=$rowfirst['name'];
}

$sql="SELECT * FROM menu_item_layers WHERE menu_item_id='".$_GET['add_item']." ' ORDER BY order_by ASC "; 
$result=mysql_query($sql);
//echo $result; die;
 if (mysql_num_rows($result) > 0) {
$layerid=1;
						// output data of each row

					while($row = mysql_fetch_assoc($result)) {  

							
 ?>						

								<?php if($one_time_flag==0) {  ?>
								
									<div class="fatchdata " id="layer<?php echo $layerid; ?>"  b="<?php echo $row['order_by']; ?>"  style="display:block;">
								
								<?php    $one_time_flag=1;  }  else {   ?>
								
								<div class="fatchdata " id="layer<?php echo $layerid; ?>" b="<?php echo $row['order_by']; ?>" dir="<?php echo $row['id']; ?>" style="display:none;">
								
								<?php   $one_time_flag=1; }   ?>
									<!-- get url from first ajax and send  to page for redirecting following url on next ajax -->
									

							<div class="h_top_heding"><h5> <?php echo $item_name;?></h5></div>
								<div class="body_in" style="">
								<h5><?php echo $row['name'];?>  </h5>

								<p><?php echo $row['description'];?> </p>
								
								<h4 class="button skip" id="yes<?php echo $row['id']; ?>" style="display:none;" dir="<?php echo $row['id']; ?>"  >Yes please</h4>
								<h4 class="button2 skip" style="display:block;" id="skip<?php echo $row['id']; ?>" dir="<?php echo $row['id']; ?>"  >Skip</h4>
								<div class="addExtrasTotal">
								<span><b>Total<b></span>
								<span style="float:right;"    ><b  id="totalPrice<?php echo $row['id'];?>">0</b></span>
								<input type="hidden" id="attributeid<?php echo $row['id'];?>" value="" >
								
								</div>
								<!--<a class="button" href="#">Yes pleafghfghse</a>-->

								<ul>
								<?php 
								$sql2="SELECT * FROM layer_lists WHERE layer_id='".$row['id']." ' ";
								$result2=mysql_query($sql2);

								if (mysql_num_rows($result2) > 0) { 

						// output data of each row

						while($column = mysql_fetch_assoc($result2)) {  //echo"<pre>"; print_r($column); die;ghfffgfgg hjghgh
								
								
								?>
								
								<li class="li_sty_fist plus_item"style="display:block">
								<div class="addItem">
								<div class="item"><?php echo $column['name'];?> </div>
								<div class="price">RMB<?php echo " ".$column['price'];?> </div>
								<div class="right addbutton"  style="cursor: pointer;" q="<?php echo $row['id'];?>" dir="<?php echo $column['id'];?>" b="<?php echo $column['name'];?>" s="<?php echo $column['price'];?>" a="<?php echo $row['id']; ?>"><a class="button1 " href="JavaScript: void(0);">+</a></div></div></li>
								
								
								<li class="li_sty_fist_last_misns minus_item" id="minus<?php echo $column['id'];?>" style="display:none">
								<div class="addItem">
								<div class="item" id="attr_quantity<?php echo $column['id'];?>" > 0</div>
								<div class="price">RMB<?php echo " ";?> <b id="single_total<?php echo $column['id'];?>">0</b></div>
								<div class="right removebutton"  style="cursor: pointer;" q="<?php echo $row['id'];?>" dir="<?php echo $column['id'];?>" b="<?php echo $column['name'];?>" s="<?php echo $column['price'];?>" ><a class="button_none  " href="JavaScript: void(0);"><img src="img/minus.png" /></a></div></div></li>
								
								<?php }   }    ?>

								

								</ul>
								</div>
</div>								
											
 <?php $layerid++; }        ?>                   

<input type="hidden" id="total_layers" value="<?php echo $layerid;?>" >

 <?php  } else {  echo 1; }  ?>

									   
								