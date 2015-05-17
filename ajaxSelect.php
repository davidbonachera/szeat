<?php require_once('class/config.inc.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php
if ($_GET['ajax']=='true') {
	if (isset($_GET['area'])) {
		
		$buildings[] = array("optionValue"	=> "", "optionDisplay"	=> "Select Building");

		$area = $db->escape($_GET['area']);
		$query = $db->query("SELECT * FROM buildings WHERE area_id='$area' AND $status=1 ORDER BY title ASC");
		
		if ($db->affected_rows > 0) {
			while($r=$db->fetch_array($query)) {
				$buildings[] = array(
								"optionValue"	=> $r['id'], 
								"optionDisplay"	=> ($lang=='cn'?($r['title_cn']==""?$r['title']:$r['title_cn']):$r['title'])
							);
			}
		}
	}
	echo json_encode($buildings);
}
?>