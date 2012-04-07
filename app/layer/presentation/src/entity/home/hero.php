<?php
$categories_objects = array();

if(isset($_SESSION["network_user_id"])){
	$network_user_id = $_SESSION["network_user_id"];
	$data= array();
	$data['network_user_id'] = $network_user_id;
	$data['limit'] = 10;
	$categories = $ContentService->getTopCategory($data);
	foreach( $categories as $category){
		$data = array();
		$data['network_user_id'] = $network_user_id;
		$data['category'] = $category['category'];
		$data['limit'] = 25;
		$object_ids = $ContentService->getTopObject($data);
		$object_ids_array = array();
		foreach($object_ids as $value){
			$object_ids_array[] = $value['object_id'];
		}
		$data = array();
		$data['object_ids'] = $object_ids_array;
		
		$objects = $ContentService->getObjectByIDs($data);
		
		$categories_objects[$category['category']] = $objects;
	}
	
}else{
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
	die();
}


?>
