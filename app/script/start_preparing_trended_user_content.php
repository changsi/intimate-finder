<?php
//INFO : SHOULD be ran after the script start_process_for_friends_script.php, the CP should be updated (CP should have the same friends as the FB) to have the best accuracy possible.
/*
Reads the info from the DB and do the following:
1st: update table object_count (FB DB) based in the user_object table (FB DB).
2nd: for each user in the user_network table (FB DB), gets the top 5 similar users from the user_user table (CP DB).
3rd: for these top 5 users, get their objects (objectIds) from the user_object table. 
4th: from the returned objects of 5 users, count the frequency of each objectId and get the top 2 objectId for each object_type (book, music, movie, etc...) base in the frequency they occur (FB DB)
5th: saves the result in the recommended_user_object table (FB DB).

Uses: ContentService->getTopContentsFromSimilarFriends(...); and ContentService->insertRecommendedUserContent(...);
*/
require dirname(__FILE__) . "/common.php";


require getContextFilePath("content.service.ContentService");
require getContextFilePath("user.service.UserService");


//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//init core_platform DB driver
$platform_DB_driver = new MySqlDB();
$platform_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["PLATFORM_DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$platform_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//initialize service
$ContentService = new ContentService();
$ContentService->setDBDriverForLiveSystem($live_DB_driver);
$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);
$UserService->setDBDriverForCorePlatForm($platform_DB_driver);


$network_id = 2; //facebook

//1st: update table object_count (FB DB) based in the user_object table (FB DB).
$ContentService->updateObjectCount();


function taskTwoToFive ($network_id) {
	global $UserService,$ContentService ;

	$data = array(
		"network_id" => $network_id
	);
	$count = $UserService->getCountOfUsers($data);
	
	//bucket_size = 300
	$bucket_size = 300;
	
	//number of times to execute =count divided by 300
	$number_of_times_to_execute = ceil($count/300);
	
	for($i = 0; $i < $number_of_times_to_execute; $i++){
		$startLine = $i == 0 ? 0 : ($i * $bucket_size);
		taskTwoToFiveBucket($startLine ,$bucket_size, $network_id);
	}
}

function taskTwoToFiveBucket($bucket_start, $bucket_limit, $network_id) {
	global $UserService,$ContentService ;
		
	$param = array (
		"start" => $bucket_start,
		"limit" => $bucket_limit,
		"network_id" => $network_id
	);
	//get the users hash user id from FB
	$users = $UserService->getUsersFromFB($param);

	if (!empty($users)) {
		foreach ($users as $user) {
			$param["hash_user_id"] = $user["hash_user_id"];
			$param["network_user_id"] = $user["network_user_id"];
			$param["limit"] = 5; //top 5 friends
			prepareAndInsertRecommendedContentForUser($param);
		}
	}
}





//$param = array(
//	"limit" => 5,//top 5 friends
//	"network_id" => 2,
//	"hash_user_id" => 1245165161,	
//	"network_user_id" => 54664
//);
function prepareAndInsertRecommendedContentForUser($param) {
	global $UserService,$ContentService ; 
	//2nd: gets the top 5 similar users from the user_user table (CP DB).
	$topFriends = $UserService->getUserNetworkFriendsAffinity($param);
	if (!empty($topFriends)) {
		$friendsIds = array();
		foreach ($topFriends as $friend) {
			 $network_user_id = $UserService->getNetworkUserFromHashUserId($friend['friend_id']);
			 if (!empty($network_user_id)) {
			 	 $friendsIds[] = $network_user_id;
			 }			
		}
		//3rd: for these top 5 users, get the top 2 content for each content type from the user_object table. 
		$types = $ContentService->getContentTypes();
		foreach ($types as $type) {
			$data = array(
				"friends_ids" => $friendsIds,
				"object_type_id" => $type["id"],
				"network_user_id" => $param["network_user_id"],
				"limit" => 2 //For top 2 contents
			);
			$sortedContentFromFriends = $ContentService->getTopContentsFromSimilarFriends($data);//this function returns an array of objects sorted by object_type_id and count
			echo "sortedContentFromFriends :\n";
			print_r($sortedContentFromFriends);
			foreach ($sortedContentFromFriends as $object) {
				$paramReco = array(
					"network_user_id" => $param["network_user_id"],
					"object_type_id" => $type["id"],
					"object_id" => $object['object_id']
				);
				$ContentService->insertRecommendedUserContent($paramReco);
			}
		
			
		}
		//DEPRECATED - was getting all the contents of the 5 friends at once - could break php array
		/*if (!empty($sortedContentFromFriends)) {
			$topNumber = 0; //init
			$prevObjectTypeId = -1;
			$numberOfObjectTypeAlreadyDone = 0;
			$topContentArr = array();
			
			foreach ($sortedContentFromFriends as $object) {
				if ($topNumber < 2){//2corresponds to the max number of objects to recommend per category
					if ($prevObjectTypeId == $object['object_type_id']) {//means that we just changed object_types
						$topContentArr[] = array(
							"object_type_id" => $object['object_type_id'],
							"object_id" => $object['object_id']
						);
						$topNumber++;
					}
					else {
						$topContentArr[] = array(
							"object_type_id" => $object['object_type_id'],
							"object_id" => $object['object_id']
						);
						$topNumber = 1;
						$prevObjectTypeId = $object['object_type_id'];
						$numberOfObjectTypeAlreadyDone++;
					}
				}
				else {
					if ($prevObjectTypeId != $object['object_type_id']) {//means that we just changed object_types
						$topContentArr[] = array(
							"object_type_id" => $object['object_type_id'],
							"object_id" => $object['object_id']
						);
						$topNumber = 1;
						$prevObjectTypeId = $object['object_type_id'];
						$numberOfObjectTypeAlreadyDone++;	
					}
				}
			}
			print_r($topContentArr);
		}*/
	}
}


taskTwoToFive($network_id);









?>
