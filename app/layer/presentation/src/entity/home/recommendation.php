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
	
	// merge close locations
	$my_locations = merge_close_locations($my_locations, 0.2);
	
	$my_interests = $UserObjectService->getObjectsByUserID(array('user_id'=>$user_id));
	$my_interests_checker = array();
	foreach($my_interests as $my_interest){
		$my_interests_checker[$my_interest['object_id']]=0;
	}
	
	$user_rank = array();
	$users_locations = array();
	$user_profiles = array();
	
	$user_common_interests = array();
	
	
	foreach($my_locations as $my_location){
		$my_location_score = $my_location["score"];
		$close_locations = get_close_locations($my_location, 0.2);
		foreach($close_locations as $close_location){
			// get the user_ids based on location id and corresponding frequency.
			$users_score_frequency = $UserLocationService->getUserIDsScoreAndFrequencyByLocationIDExceptforMyself($close_location, $user_id);
			// get detailed location information based on location_id
			$close_location_detail = $LocationService->getLocationByID($close_location);
			$close_location_detail = $close_location_detail[0];
		
			foreach($users_score_frequency as $user_score_frequency){
				$user_location_score = $user_score_frequency["score"]+$my_location_score;
				// add or update user_id => score map
				if(isset($user_rank[$user_score_frequency['user_id']])){
					$previous = $user_rank[$user_score_frequency['user_id']]['score'];
					$user_rank[$user_score_frequency['user_id']]['score'] = $previous + $user_location_score;
				}else{
					$user_rank[$user_score_frequency['user_id']] = array('user_id'=>$user_score_frequency['user_id'], 'score'=>$user_location_score);
				}
				// add user location map
				if(isset($users_locations[$user_score_frequency['user_id']])){
					if(!isset($users_locations[$user_score_frequency['user_id']][$close_location_detail['location_id']])){
						$users_locations[$user_score_frequency['user_id']][$close_location_detail['location_id']] = $close_location_detail;
						$users_locations[$user_score_frequency['user_id']][$close_location_detail['location_id']]['frequency'] = $user_score_frequency["frequency"];
					}
				}else{
					$users_locations[$user_score_frequency['user_id']] = array($close_location_detail['location_id']=>$close_location_detail);
					$users_locations[$user_score_frequency['user_id']][$close_location_detail['location_id']]['frequency'] = $user_score_frequency["frequency"];
				}
			}
		}
	}
	//$users_new_locations = find_new_location($my_locations, $users_locations);
	$my_locations = location_sort_by_frequency($my_locations);
	
	// calculate interest score
	foreach($user_rank as $user_id=>$user_location_score){
		$user_insterests = $UserObjectService->getObjectsByUserID(array('user_id'=>$user_id));
		$score = calculate_interest_score($my_interests_checker, $user_insterests, $user_id);
		$user_rank[$user_id]['score'] = $user_location_score['score'] + $score;
	}
	
	$user_rank = user_rank_sort_by_score($user_rank);
	//print_r($user_rank);
	//echo "<br>";
	
	foreach ($users_locations as $user_id_tmp => $location){
		
		$user_profile_tmp = $UserService->getUserInfoFromLiveSystem(array('user_id'=>$user_id_tmp));
		//print_r($user_profile_tmp);
		$user_profiles[$user_id_tmp] = $user_profile_tmp[0];
		
	}
	
	
	//$close_locations = get_close_locations(array("latitude"=>40.694024500161, "longitude"=>-73.986695005749), 0.5);
	//print_r($close_locations);
}
else{
	header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
	die();
}

function find_new_location($my_locations, $users_locations){
	$result = array();
	foreach($users_locations as $user_id => $user_locations){
		$all_locations = get_locations_by_user_id($user_id);
		$new_locations = array();
		foreach($all_locations as $location_id => $location){
			if(!isset($my_locations[$location_id])&&!isset($user_locations[$location_id])){
				$new_locations[$location_id] = $location;
			}
		}
		$result[$user_id] = $new_locations;
	}
	return $result;
}

function calculate_interest_score($my_insterests, $user_interests, $user_id){
	global $user_common_interests;
	$score = 0;
	foreach($user_interests as $user_interest){
		if(isset($my_insterests[$user_interest['object_id']])){
			if(isset($user_common_interests[$user_id])){
				$user_common_interests[$user_id][] = $user_interest['object_name'];
			}else{
				$user_common_interests[$user_id] = array();
				$user_common_interests[$user_id][] = $user_interest['object_name'];
			}
			$score++;
		}
	}
	return $score;
}

function get_locations_by_user_id($user_id){
	global $UserLocationService, $LocationService;
	$data = array(
			'user_id'	=>	$user_id
	);
	
	$locations = $UserLocationService->getLocationAndScoreByUserID($data);
	$location_tmp = array();
	$location_ids = array();
	foreach ($locations as $location){
		$location_ids[] = $location['location_id'];
		$location_tmp[$location['location_id']]['score'] = $location['score'];
		$location_tmp[$location['location_id']]['frequency'] = $location['frequency'];
	}
	$locations = $location_tmp;
	$location_tmp = null;
	$result = array();
	$locations_db = $LocationService->getLocationsByIDS(array("location_ids"=>implode(',', $location_ids)));
	foreach($locations_db as $location){
		$location_id = $location['location_id'];
		$score = $locations[$location_id]['score'];
		$frequency = $locations[$location_id]['frequency'];
		$location['score'] = $score;
		$location['frequency'] = $frequency;
		$result[$location_id] = $location;
	}
	return $result;
}

function user_rank_sort_by_score($user_rank){
	usort($user_rank, "user_rank_sort_function");
	return $user_rank;
}


function location_sort_by_frequency($locations){
	usort($locations, "location_sort_function");
	return $locations;
}

function user_rank_sort_function($a, $b){
	if($a['score']==$b['score']){
		return 0;
	}
	return ($a['score']>$b['score'])? -1: 1;
}

function location_sort_function($a, $b){
	if($a['frequency']==$b['frequency']){
		return 0;
	}
	return ($a['frequency']>$b['frequency'])? -1: 1;
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

function merge_close_locations($locations, $distance){
		
		$tmp_locations = array();
		foreach($locations as $location){
			$tmp_locations[] = $location;
		}
		$locations = $tmp_locations;
		for($i=0; $i<sizeof($locations); $i++){
			if(isset($locations[$i])&&!empty($locations[$i])){
				$location1 = $locations[$i];
				$lat1 = $location1['latitude'];
				$lon1 = $location1['longitude'];
				for($j=$i+1;$j<sizeof($locations); $j++){
					if(isset($locations[$j])&&!empty($locations[$j])){
						$location2 = $locations[$j];
						$lat2 = $location2['latitude'];
						$lon2 = $location2['longitude'];
						if(calculate_distance($lat1, $lon1, $lat2, $lon2)<$distance){
							unset($locations[$j]);
						}
					}
					
				}
			}
			
		}
		$result = array();
		foreach($locations as $location){
			if(isset($location) && !empty($location)){
				$result[$location['location_id']] = $location;
			}
		}
		return $result;
	}


?>