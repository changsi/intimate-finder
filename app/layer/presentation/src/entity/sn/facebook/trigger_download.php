<?php
require getLibFilePath('sn.util.facebook_data_wrapper');

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;

$user_id = $SNFacebookService->getUserId();

if($user_id) {
	//echo $user_id."\n";
	$_SESSION["user_id"] = $user_id;
	$_SESSION["access_token"] = $SNFacebookService->getAccessToken();
	$data = array("user_id"=>$_SESSION["user_id"], "access_token"=>$_SESSION["access_token"]);
	$data = array("user_id"=>$_SESSION["user_id"], "progress"=>0, "control_flag"=>0);
	$UserProgressionService->insertUserProgress($data);
	$command = "/usr/bin/php ".getScriptFilePath("fetching_facebook_checkin")." ".$_SESSION["user_id"]." ".$_SESSION["access_token"].' > /dev/null &';
	
	$handler = exec($command);
	
	//$data = array("user_id"=>$_SESSION["user_id"], "progress"=>100, "control_flag"=>0);
	//$UserProgressionService->insertUserProgress($data);
	//die();
}
else{
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
	die();
}


function store_user_profile($user_profile,$UserService){
	$data = get_data_from_profile($user_profile);
	$UserService->insertUser($data);
	return;
}

function store_user_checkins($user_checkins, $LocationService, $UserLocationService){

	$user_checkins = $user_checkins['data'];

	foreach($user_checkins as $checkin){
		$data = get_data_from_checkin($checkin);
		//print_r($data);
		$LocationService->insertLocation($data);
		$UserLocationService->insertUserLocation($data);
	}

}

function fetch_checkin($data){
	global $UserFriendService, $UserLocationService, $LocationService, $UserProgressionService, $UserService, $SNFacebookService;
	$user_id = $data['user_id'];
	$access_token = $data['access_token'];


	

	$data = array('user_id'=>$user_id, 'access_token'=>$access_token, 'control_flag'=>0);
	$SNFacebookService->setAccessToken($data);
	$result = $SNFacebookService->getEverythingExceptFriendsCheckin($data);
	//print_r($result);

	//$user_profile = json_decode($result[0]['body'], true, 512);
	$user_checkins = json_decode($result[1]['body'], true, 512);
	$user_friends = json_decode($result[2]['body'], true, 512);

	$progress_data = array('user_id'=>$user_id, 'control_flag'=>0);

	store_user_checkins($user_checkins,$LocationService,$UserLocationService);
	
	$progress_data['progress'] = 5;
	$UserProgressionService->insertUserProgress($progress_data);
	
	$user_friends_ids = get_ids_from_friends($user_friends);
	$batch_num = floor(count( $user_friends_ids )/25);
	$last_batch_num = count($user_friends_ids)%25;

	$data = array("friends"=>$user_friends_ids);

	echo "batch number:".$batch_num."		last batch number:".$last_batch_num."\n";

	$k = 1;
	if($batch_num>0){
		for($i =0; $i<$batch_num*25; $i=$i+25){
			echo "batch: ".$k."\n";
			
			$data['start'] = $i;
			$data['limit'] = 25;
			$objects = $SNFacebookService->getFriendProfileInfoAndCheckin($data);
			for($j = 0; $j<25; $j++){
				$friend_profile = json_decode($objects[$j*2]['body'], true, 512);
				$friend_checkin = json_decode($objects[$j*2+1]['body'], true, 512);
				//print_r($friend_profile);
				store_user_profile($friend_profile,$UserService);
				store_user_checkins($friend_checkin,$LocationService,$UserLocationService);
			}
			$progress_data['progress'] = $progress_data['progress']+round(95/($batch_num+1));
			$UserProgressionService->insertUserProgress($progress_data);
			$k++;
		}
	}
	if($last_batch_num>0){
		$data['start'] = $batch_num * 25;
		$data['limit'] = $last_batch_num;
		$objects = $SNFacebookService->getFriendProfileInfoAndCheckin($data);
		for($j=0; $j<$last_batch_num; $j++){
			$friend_profile = json_decode($objects[$j*2]['body'], true, 512);
			$friend_checkin = json_decode($objects[$j*2+1]['body'], true, 512);
			store_user_profile($friend_profile,$UserService);
			store_user_checkins($friend_checkin,$LocationService,$UserLocationService);
		}
	}

	
}

?>