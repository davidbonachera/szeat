<?php 

// require_once('class/config.inc.php');
// require_once("class/Pagination.class.php");
// require_once('includes/functions.php');

if ($_GET) {
	$area 		= $db->escape($_GET['area']);
	$building 	= $db->escape($_GET['building']);
	$cuisines 	= $db->escape($_GET['cuisines']);
	
	if (checkFeild($area)) {
		$areaName = getData('areas','title',$area);
		$where = "WHERE ra.area_id=$area";
	}
	if (checkFeild($building)) {
		$buildingName = getData('buildings','title',$building);
		$where = isset($where) ? $where." AND rb.building_id=$building":"WHERE rb.building_id=$building";
	}
	if (checkFeild($cuisines)) {
		$cuisineName = getData('cuisines','title',$cuisines);
	} else {
		$cuisineName = NULL;
	}
	
	$where = isset($where) ? $where." AND r.status=1":"WHERE r.status=1";
	
	if (isset($_GET['sort'])) {
		if 	   ($_GET['sort']=='best') 		$orderBy = 'ORDER BY id ASC';
		elseif ($_GET['sort']=='new') 		$orderBy = 'ORDER BY date DESC';
		elseif ($_GET['sort']=='rating') 	$orderBy = 'ORDER BY ratings DESC';
		elseif ($_GET['sort']=='name') 		$orderBy = 'ORDER BY name ASC';
		else 								$orderBy = NULL;
	} else {
		$orderBy = NULL;
	}
	$searchQuery = "SELECT r.id AS restaurant_id, r.*, ra.area_id, rb.building_id, 
				   (
				   		SELECT GROUP_CONCAT(cuisine_id separator ', ') FROM restaurants_cuisines AS rc WHERE rc.restaurant_id=r.id
				   ) AS cuisines,
				   (
					   SELECT AVG(ratings) FROM `ratings` AS rr WHERE rr.restaurant_id=r.id
				   ) AS ratings
				   	FROM restaurants AS r 
				    LEFT JOIN restaurants_areas AS ra ON r.id=ra.restaurant_id
				    LEFT JOIN restaurants_buildings AS rb ON r.id=rb.restaurant_id
				    $where GROUP BY id $orderBy";

	$search = $db->query($searchQuery);
	$resultCount = $db->affected_rows;
	
} else {
	header("Location: index.php");
	exit;
}
?>