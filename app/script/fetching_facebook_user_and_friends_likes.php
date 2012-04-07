<?php
require dirname(__FILE__) . "/common.php";
require getContextFilePath("sn.service.SNFacebookService");
require getContextFilePath("user.service.UserService");
require getContextFilePath("content.service.ContentService");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);


$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);

$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);

$ContentService = new ContentService();
$ContentService->setDBDriverForLiveSystem($live_DB_driver);

if ($argc == 0 || !isset($argv[1]) || !is_numeric($argv[1])) {
	die("USER_ID UNDEFINED!");
}

$user_id = $argv[1];
echo 'Network User id  :'.$user_id." \n ";	

$data = array(
	"network_user_id" => $user_id,
	"network_id" => 2
);
$token = $UserService->getUserTokenFromLiveSystem($data);
if (isset($token[0]['access_token'])) {
	$access_token = $token[0]['access_token'];
	$SNFacebookService->setAccessToken($token[0]);
	$user_data = array(
		"network_user_id" => $user_id
	);
	$likes_data = $SNFacebookService->getUserAndFriendsLikes($user_data);
	if($likes_data){
		$user_likes = json_decode($likes_data[0]['body'], true, 512);
		$user_friends = json_decode($likes_data[1]['body'], true, 512);
		$friends_likes = json_decode($likes_data[2]['body'], true, 512);
		$friends_pictures = json_decode($likes_data[3]['body'], true, 512);
		$friends_pictures = $friends_pictures['data'];
		$pictures = array();
		foreach($friends_pictures as $value){
			$pictures[$value['uid']]=$value['pic_big'];
		}
		
		$friends_pictures = $pictures;
		$friends_likes[$user_id] = $user_likes;
		$user_friends = $user_friends['data'];
		
		foreach($user_friends as $value){
			$data = array();
			$data['network_user_id'] = $value['id'];
			$data['network_id'] = 2; 
			$data['name'] = $value['name'];
			$data['screen_name'] = $value['name'];
			$data['picture_url'] = $friends_pictures[$value['id']];
			$UserService->insertUserNetwork($data);
			$friend_relationship = array();
			$friend_relationship['user_id_from'] = $user_id;
			$friend_relationship['user_id_to'] = $value['id'];
			$friend_relationship['network_id'] = '2';
			$UserService->insertUserNetworkFriend($friend_relationship);
		}
		$objects_to_fetch = array();
		$category_count = array();
		$object_count = array();
		foreach($friends_likes as $friend_id=>$friend_likes){
			$friend_likes = $friend_likes['data'];
			foreach($friend_likes as $value){
				$data = array();
				$data['network_user_id'] = $friend_id;
				$data['object_id'] = $value['id'];
				$data['created_time'] = (isset($value['created_time'])?$value['created_time']:'0000-00-00 00:00:00') ;
				
				if(!isset($category_count[$value["category"]])){
					$category_count[$value["category"]] =1;
				}
				else{
					$category_count[$value["category"]] =$category_count[$value["category"]]+1;
				}
				
				if(!isset($object_count[$value["id"]])){
				$object_count[$value["id"]] = array("category"=> $value["category"], "likes"=> 0,"count"=>1);
			}
			else{
				$tmp = $object_count[$value["id"]]['count'];
				$object_count[$value["id"]]['count'] =$tmp+1;
			}
				
				$ContentService->insertUserObject($data);
				if(!isset($objects_to_fetch[$value['id']])){
					$object = $ContentService->getObjectByID(array("object_id"=>$value['id']));
					if(empty($object)){
						$objects_to_fetch[$value['id']]=$value['id'];
					}
					
				}
			}
		}
		
		echo "there are ".count($objects_to_fetch)." need to be fetched\n";

		$batch_num = floor(count( $objects_to_fetch )/1000);
		$last_batch_num = count($objects_to_fetch)%1000;
		$data = array("data"=>$objects_to_fetch);
		$detailed_objects = array();
		echo "batch number:".$batch_num."		last batch number:".$last_batch_num."\n";
		$k = 1;
		if($batch_num>0){
			for($i =0; $i<$batch_num*1000; $i=$i+1000){
				echo "batch: ".$k."\n";
				$data['start'] = $i;
				$data['limit'] = 1000;
				$objects = $SNFacebookService->getObjectDetailedInfor($data);
				for($j = 0; $j<50; $j++){
					$object_result = json_decode($objects[$j]['body'], true, 512);
					$detailed_objects = array_merge($object_result,$detailed_objects);
				}
				$k++;
			}
		}
		
		if($last_batch_num>0){
			$data['start'] = $batch_num * 1000;
			$data['limit'] = $last_batch_num;
			$objects = $SNFacebookService->getObjectDetailedInfor($data);
			for($j=0; $j<floor(($last_batch_num/20))+1; $j++){
				$object_result = json_decode($objects[$j]['body'], true, 512);
				$detailed_objects = array_merge($object_result,$detailed_objects);
			}
			//echo (floor(($last_batch_num/100))+1)."\n";
		}
		echo count($detailed_objects)."\n";
		
		$log = fopen("log.txt", "w");
		$count_error = 0;
		foreach($detailed_objects as $object){
			$data = array();
			$data['object_id'] = $object['id'];
			$data["name"] = $object['name'];
			$data["category"] = isset($object['category'])? $object['category']:"unknown";
			$data["picture_url"] ="";
			$data['link']=isset($object['link'])? $object['link']:"";
			$data['likes'] =0;
			$data['website'] ="";
			$data['description'] = isset($object['description'])? $object['description']: (isset($object['about'])? $object['about']:"");
			if(strlen($data['description'])>5000){
				$data['description'] = substr($data['description'],0,5000);
			}
			$result = $ContentService->insertObject($data);
			if(!$result){
				$count_error = $count_error+1;
				fwrite($log, $data['object_id']." can not be inserted\n");
				fwrite($log, $data['object_id']."\n".$data['name']."\n".$data['category']."\n".$data['link']."\n".$data['website']."\n".$data['description']."\n");
			}
		}
		fclose($log);
		echo $count_error."\n";
		
		foreach($category_count as $key=>$value){
			$data = array();
			$data['network_user_id'] =  $user_id;
			$data['category'] = $key;
			$data['count'] = $value;
			$data['control_flag'] = 0;
			$ContentService->insertUserFriendsCategory($data);
		}
		
		foreach($object_count as $key=>$value){
			$data = array();
			$data['network_user_id'] =  $user_id;
			$data['object_id'] = $key;
			$data['category'] = $value['category'];
			$data['count'] = $value['count'];
			$data['likes'] = $value['likes'];
			$data['control_flag'] = 0;
			$ContentService->insertUserFriendsObject($data);
		}
		
		
	}

	
}



?>
