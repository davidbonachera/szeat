<?php 
if (isset($_GET['restaurant'])) {
	$rID = $db->escape($_GET['id']);
	$res = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.*, rb.* FROM restaurants AS r
							 LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
							 LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id 
							 WHERE r.id='$rID' AND r.status=1 LIMIT 1");
}elseif (isset($_GET['restaurant'])) {
	$str = str_replace(array('_','+'), array('&',' '), html_entity_decode($_GET['restaurant']));
	$res = $db->query_first("SELECT r.id AS restaurant_id, r.*, ra.*, rb.* FROM restaurants AS r
							 LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
							 LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id 
							 WHERE name LIKE '%$str%' AND r.status=1 LIMIT 1");
} else {
	header("Location: index.php");
	exit;
}