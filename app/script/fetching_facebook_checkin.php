<?php 

function store_user_profile($user_profile,$UserService){
	$data = get_data_from_profile($user_profile);
	$UserService->insertUser($data);
	return;
}

function store_user_checkins($user_id, $user_checkins, $LocationService, $UserLocationService){

	$user_checkins = $user_checkins['data'];
	if(isset($user_checkins)&&!empty($user_checkins)){
		foreach($user_checkins as $checkin){
			$data = get_data_from_checkin($checkin);
			//print_r($data);
			$data['user_id'] = $user_id;
			$LocationService->insertLocation($data);
			$UserLocationService->insertUserLocation($data);
		}
	}
	
	
}

function store_user_friends($user_id, $friends_ids, $UserFriendService){
	$data = array('user_id_from'=>$user_id);
	foreach($friends_ids as $friend_id){
		$data['user_id_to'] = $friend_id;
		$UserFriendService->insertUserFriend($data);
	}
}

function fetch_checkin($data){
	
	$user_id = $data['user_id'];
	$access_token = $data['access_token'];
	
	define("APP_PATH", dirname(dirname(__FILE__)));
	
	require APP_PATH . "/lib/cms/init.php";
	require getConfigFilePath("config");
	require getConfigFilePath("db_config");
	require getLibFilePath("db.driver.MySqlDB");
	require getLibFilePath('sn.util.facebook_data_wrapper');
	
	
	require getContextFilePath("sn.service.SNFacebookService");
	require getContextFilePath("location.service.LocationService");
	require getContextFilePath("location.service.UserLocationService");
	require getContextFilePath("user.service.UserService");
	require getContextFilePath("user.service.UserFriendService");
	require getContextFilePath("user.service.UserProgressionService");
	
	//init live system DB driver
	$live_DB_driver = new MySqlDB();
	$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], '', false, true);
	$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
	
	//initialize service
	$SNFacebookService = new SNFacebookService();
	$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);
	
	$LocationService = new LocationService();
	$LocationService->setDBDriverForLiveSystem($live_DB_driver);
	
	$UserLocationService = new UserLocationService();
	$UserLocationService->setDBDriverForLiveSystem($live_DB_driver);
	
	$UserService = new UserService();
	$UserService->setDBDriverForLiveSystem($live_DB_driver);
	
	$UserProgressionService = new UserProgressionService();
	$UserProgressionService->setDBDriverForLiveSystem($live_DB_driver);
	
	$UserFriendService = new UserFriendService();
	$UserFriendService->setDBDriverForLiveSystem($live_DB_driver);
	
	$data = array('user_id'=>$user_id, 'access_token'=>$access_token);
	$SNFacebookService->setAccessToken($data);
	$result = $SNFacebookService->getEverythingExceptFriendsCheckin($data);
	//print_r($result);
	echo "finish fetching my data!\n";
	
	$user_profile = json_decode($result[0]['body'], true, 512);
	$user_checkins = json_decode($result[1]['body'], true, 512);
	$user_friends = json_decode($result[2]['body'], true, 512);
	//print_r($user_friends);
	//print_r($user_profile);
	
	//print_r (get_data_from_profile($user_profile));
	$progress_data = array('user_id'=>$user_id, 'control_flag'=>0);
	
	store_user_checkins($user_id, $user_checkins,$LocationService,$UserLocationService);
	$progress_data['progress'] = 5;
	$UserProgressionService->insertUserProgress($progress_data);
	echo "progress 5\n";
	
	$user_friends_ids = get_ids_from_friends($user_friends);
	store_user_friends($user_id, $user_friends_ids, $UserFriendService);
	
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
				store_user_checkins($friend_profile['id'], $friend_checkin,$LocationService,$UserLocationService);
			}
			$progress_data['progress'] = $progress_data['progress']+round(95/($batch_num+1));
			$UserProgressionService->insertUserProgress($progress_data);
			echo "progress ".$progress_data['progress']."\n";
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
			store_user_checkins($friend_profile['id'],$friend_checkin,$LocationService,$UserLocationService);
		}
	}
	$data = array("user_id"=>$user_id, "progress"=>100, "control_flag"=>0);
	$UserProgressionService->insertUserProgress($data);
	
	/*
	
	
	
	
	
	foreach($old_objects as $old_object){
		$network_object_id = $old_object['id'];
		if(!isset($detailed_objects[$network_object_id])){
			echo $network_object_id."\n";
		}
		else{
			$object_infor = $detailed_objects[$network_object_id];
			$para = array();
			$para['object_id'] = $old_object['object_id'];
			$para['object_type_id'] = $old_object['object_type_id'];
			$para['description'] = isset($object_infor['description']) ? $object_infor['description'] : (isset($object_infor['about'])?$object_infor['about']:"");
			$para['plot_outline'] = isset($object_infor['plot_outline']) ? $object_infor['plot_outline'] : "";
			$para['tv_network'] = isset($object_infor['tv_network']) ? $object_infor['tv_network'] : "";
			$para['control_flag'] = 1;
			$ContentService->updateObject($para);
		}
	
	}
	
	//print_r($detailed_objects);
	 
	 */
}

$user_id = $argv[1];
$access_token = $argv[2];
$data = array('user_id'=>$user_id, 'access_token'=>$access_token);
fetch_checkin($data);


?>
