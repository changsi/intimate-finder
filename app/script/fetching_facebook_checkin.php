<?php 

function store_user_profile($user_profile,$UserService){
	$data = get_data_from_profile($user_profile);
	$UserService->insertUser($data);
	return;
}

function store_user_checkins($user_checkins, $LocationService, $UserLocationService){

	$user_checkins = $user_checkins['data'];
	
	foreach($user_checkins as $checkin){
		$data = get_data_from_checkin($checkin);
		print_r($data);
		$LocationService->insertLocation($data);
		$UserLocationService->insertUserLocation($data);
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
	
	$UserFriendService = new UserFriendService();
	$UserFriendService->setDBDriverForLiveSystem($live_DB_driver);
	
	$data = array('user_id'=>$user_id, 'access_token'=>$access_token);
	$SNFacebookService->setAccessToken($data);
	$result = $SNFacebookService->getEverythingExceptFriendsCheckin($data);
	
	//$user_profile = json_decode($result[0]['body'], true, 512);
	$user_checkins = json_decode($result[1]['body'], true, 512);
	$user_friends = json_decode($result[2]['body'], true, 512);
	store_user_checkins($user_checkins,$LocationService,$UserLocationService);
	
	
	/*
	$batch_num = floor(count( $new_ids )/5000);
	$last_batch_num = count($new_ids)%5000;
	$data = array("data"=>$new_ids);
	$detailed_objects = array();
	echo "batch number:".$batch_num."		last batch number:".$last_batch_num."\n";
	$k = 1;
	if($batch_num>0){
		for($i =0; $i<$batch_num*5000; $i=$i+5000){
			echo "batch: ".$k."\n";
			$data['start'] = $i;
			$data['limit'] = 5000;
			$objects = $SNFacebookService->getObjectDetailedInfor($data);
			for($j = 0; $j<50; $j++){
				$detailed_objects = array_merge(json_decode($objects[$j]['body'], true, 512),$detailed_objects);
			}
			$k++;
		}
	}
	if($last_batch_num>0){
		$data['start'] = $batch_num * 5000;
		$data['limit'] = $last_batch_num;
		$objects = $SNFacebookService->getObjectDetailedInfor($data);
		for($j=0; $j<floor(($last_batch_num/100))+1; $j++){
			//echo $j."\n";
			//print_r(json_decode($objects[$j]['body'], true, 512));
			//echo "\n";
			$detailed_objects = array_merge((array)json_decode($objects[$j]['body'], true, 512),(array)$detailed_objects);
		}
		//echo (floor(($last_batch_num/100))+1)."\n";
	}
	
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


$data = array('user_id'=>100001504551254, 'access_token'=>'AAAFcKdcSXv4BAA4Xqtm6g3CbHxJnxeUGKbCYCKB9n17XacSm7fH1hd9HRRHaIRGZCQ42HDvVZCOIlOy49oa30r3vQPwg1XJgGiXHAGJJBqMzk0a97r');
fetch_checkin($data);


?>
