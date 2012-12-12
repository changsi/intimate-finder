<?php
require getLibFilePath("io.CurlUtil");
require getLibFilePath("util.DistanceHelper");

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : NULL;

if($user_id) {
	$my_profile = $UserService->getUserInfoFromLiveSystem(array('user_id'=>$user_id));
	$my_profile = $my_profile[0];
	$my_user_name = $my_profile['name'];
	$my_locations = get_locations_by_user_id($user_id);
	$my_friends = $UserFriendService->getUserFriends(array('user_id_from'=>$user_id));
	//print_r($my_friends);
	$locations = array();
	foreach($my_friends as $my_friend){
		$my_friend_locations = get_locations_by_user_id($my_friend['user_id_to']);
		if(!empty($my_friend_locations)){
			foreach($my_friend_locations as $my_friend_location){
				if(!isset($locations[$my_friend_location['location_id']])){
					$locations[$my_friend_location['location_id']] = array('count'=>1, 'info'=>$my_friend_location);
				}else{
					$locations[$my_friend_location['location_id']]['count'] = $locations[$my_friend_location['location_id']]['count']+1;
				}		
			}
		}
		
		
	}
	$locations = location_sort_by_frequency($locations);
	
}else{
	header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
	die();
}

function location_sort_by_frequency($locations){
	usort($locations, "location_sort_function");
	return $locations;
}

function location_sort_function($a, $b){
	if($a['count']==$b['count']){
		return 0;
	}
	return ($a['count']>$b['count'])? -1: 1;
}


function get_locations_by_user_id($user_id){
	global $UserLocationService, $LocationService;
	$data = array(
			'user_id'	=>	$user_id
	);

	$locations = $UserLocationService->getLocationAndScoreByUserID($data);
	$location_tmp = array();
	$location_ids = array();
	if(!empty($locations)){
		foreach ($locations as $location){
			$location_ids[] = $location['location_id'];
			$location_tmp[$location['location_id']]['score'] = $location['score'];
			$location_tmp[$location['location_id']]['frequency'] = $location['frequency'];
		}
		$locations = $location_tmp;
		$location_tmp = null;
		$result = array();
		$result = $LocationService->getLocationsByIDS(array("location_ids"=>implode(',', $location_ids))); 
		
		return $result;
	}else{
		return false;
	}
	
}

?>