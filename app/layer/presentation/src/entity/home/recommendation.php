<?php
require getLibFilePath("io.CurlUtil");
$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : NULL;

if($user_id) {
	 
	$my_locations = get_locations_by_user_id($user_id);
	$friend_frequency = array();
	
	foreach($my_locations as $my_location){
		$my_location_frequency = $my_location["frequency"];
		$close_locations = get_close_locations($my_location, 0.5);
		foreach($close_locations as $close_location){
			$users_frequency = $UserLocationService->getUserIDsByLocationID($close_location);
			foreach($users_frequency as $user_frequency){
				$user_location_frequency = $user_frequency["frequency"]+$my_location_frequency;
				if(isset($friend_frequency[$user_frequency['user_id']])){
					$previous = $friend_frequency[$user_frequency['user_id']];
					$friend_frequency[$user_frequency['user_id']] = $previous + $user_location_frequency;
				}else{
					$friend_frequency[$user_frequency['user_id']] = $user_location_frequency;
				}
			}
		}
	}
	print_r($friend_frequency);
	
	//$close_locations = get_close_locations(array("latitude"=>40.694024500161, "longitude"=>-73.986695005749), 0.5);
	//print_r($close_locations);
}
else{
	header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
	die();
}

function get_locations_by_user_id($user_id){
	global $UserLocationService, $LocationService;
	$data = array(
			'user_id'	=>	$user_id
	);
	
	$locations = $UserLocationService->getLocationByUserID($data);
	$location_tmp = array();
	$location_ids = array();
	foreach ($locations as $location){
		$location_ids[] = $location['location_id'];
		$location_tmp[$location['location_id']] = $location['frequency'];
	}
	$locations = $location_tmp;
	$location_tmp = null;
	
	$locations_db = $LocationService->getLocationsByIDS(array("location_ids"=>implode(',', $location_ids)));
	foreach($locations_db as $location){
		$location_id = $location['location_id'];
		$frequency = $locations[$location_id];
		$location['frequency'] = $frequency;
		$locations[$location_id] = $location;
	}
	return $locations;
}

//data {"latitude"=>40.694024500161, "longitude"=>-73.986695005749}
function get_close_locations($data, $mile){
	
	
	$latitude = $data["latitude"];
	$longitude = $data["longitude"];
	
	$low_x = $longitude - $mile/48;
	$high_x = $longitude + $mile/48;
	
	$low_y = $latitude - $mile/60;
	$high_y = $latitude + $mile/60;
	$query_param = array("low_x"=>$low_x, "low_y"=> $low_y, "high_x"=> $high_x, "high_y"=>$high_y);
	$location_json = json_encode($query_param);
	$curl = new cURL(false);
	
	$result = $curl->post('http://localhost:8080/KDTree/query','data='.$location_json);
	return json_decode($result, true);
}

?>