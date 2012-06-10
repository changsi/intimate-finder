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

function init(){
	define("APP_PATH", dirname(dirname(__FILE__)));
	
	require APP_PATH . "/lib/cms/init.php";
	require getConfigFilePath("config");
	require getLibFilePath("db.driver.MySqlDB");
	require getLibFilePath('sn.util.facebook_data_wrapper');
	
	
	require getContextFilePath("sn.service.SNFacebookService");
	require getContextFilePath("location.service.LocationService");
	require getContextFilePath("location.service.UserLocationService");
	require getContextFilePath("user.service.UserService");
	require getContextFilePath("user.service.UserFriendService");
	require getContextFilePath("user.service.UserFBDownloadProgressionService");
}

function filter_registered_users($user_ids,$UserService){
	
	$result = array();
	foreach($user_ids as $user_id){
		if(!$UserService->isRegisteredUser(array('user_id'=>$user_id))){
			$result[] = $user_id;
		}
	}
	return $result;
}

function fetch_checkin($data){
	
	$user_id = $data['user_id'];
	$access_token = $data['access_token'];
	
	
	require getConfigFilePath("db_config");
	
	
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
	
	$UserFBDownloadProgressionService = new UserFBDownloadProgressionService();
	$UserFBDownloadProgressionService->setDBDriverForLiveSystem($live_DB_driver);
	
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
	$progress_data = array('user_id'=>$user_id, "control_flag"=>1);
	
	store_user_checkins($user_id, $user_checkins,$LocationService,$UserLocationService);
	$progress_data['progress'] = 5;
	$UserFBDownloadProgressionService->insertUserProgress($progress_data);
	echo "progress 5\n";
	
	$user_friends_ids = get_ids_from_friends($user_friends);
	store_user_friends($user_id, $user_friends_ids, $UserFriendService);
	
	// filter registered users
	$user_friends_ids = filter_registered_users($user_friends_ids,$UserService);
	
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
			$progress_data['progress'] = $progress_data['progress']+round(75/($batch_num+1));
			$UserFBDownloadProgressionService->insertUserProgress($progress_data);
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
	$data = array("user_id"=>$user_id, "progress"=>80, "control_flag"=>1);
	$UserFBDownloadProgressionService->insertUserProgress($data);
	
}

function fetch_like($data){
	$user_id = $data['user_id'];
	$access_token = $data['access_token'];
	
	require getConfigFilePath("db_config");
	//require getLibFilePath("db.driver.MySqlDB");
	//require getLibFilePath('sn.util.facebook_data_wrapper');
	
	
	//require getContextFilePath("sn.service.SNFacebookService");
	require getContextFilePath("content.service.ObjectService");
	require getContextFilePath("content.service.UserObjectService");
	//require getContextFilePath("user.service.UserFBDownloadProgressionService");
	
	//init live system DB driver
	$live_DB_driver = new MySqlDB();
	$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], '', false, true);
	$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
	
	//initialize service
	$SNFacebookService = new SNFacebookService();
	$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);
	$UserObjectService = new UserObjectService();
	$UserObjectService->setDBDriverForLiveSystem($live_DB_driver);
	$ObjectService = new ObjectService();
	$ObjectService->setDBDriverForLiveSystem($live_DB_driver);
	$UserFBDownloadProgressionService = new UserFBDownloadProgressionService();
	$UserFBDownloadProgressionService->setDBDriverForLiveSystem($live_DB_driver);
	
	$data = array('user_id'=>$user_id, 'access_token'=>$access_token);
	$SNFacebookService->setAccessToken($data);
	echo "start downloading likes data.\n";
	$likes_data = $SNFacebookService->getUserAndFriendsLikes($data);
	if($likes_data){
		$user_likes = json_decode($likes_data[0]['body'], true, 512);
		$friends_likes = json_decode($likes_data[2]['body'], true, 512);
		print_r($friends_likes);
		$friends_likes[$user_id] = $user_likes;
		foreach($friends_likes as $friend_id=>$friend_likes){
			$friend_likes = $friend_likes['data'];
			if(empty($friend_likes)){
				echo "$friend_id\n";
			}else{
				foreach($friend_likes as $value){
					$data = array();
					$data['user_id'] = $friend_id;
					$data['object_id'] = $value['id'];
					$data['object_name'] = (isset($value['name'])?$value['name']:'') ;
					$data['category'] = (isset($value['category'])?$value['category']:'') ;
					$data['created_time'] = (isset($value['created_time'])?$value['created_time']:'0000-00-00 00:00:00') ;
				
					$UserObjectService->insertUserObject($data);
				}
			}
			
		}
	}
	$data = array("user_id"=>$user_id, "progress"=>100, "control_flag"=>0);
	$UserFBDownloadProgressionService->insertUserProgress($data);
	echo "progress 100\n";
}

$user_id = $argv[1];
$access_token = $argv[2];
$data = array('user_id'=>$user_id, 'access_token'=>$access_token);
init();
fetch_checkin($data);
fetch_like($data);

?>
