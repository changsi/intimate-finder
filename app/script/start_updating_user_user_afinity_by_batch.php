<?php
//INFO: 
/*
 * 1. Gets the total no. of users from CP
 * 2. Gets its affined users with affinity more then threshold level from CP
 * 3. Calcluates new affinty
 * 4. insert/update it on live db i.e. user_user
 */
	require dirname(__FILE__) . "/common.php";
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
	$UserService = new UserService();
	$UserService->setDBDriverForLiveSystem($live_DB_driver);
	$UserService->setDBDriverForCorePlatForm($platform_DB_driver);
	
	//initialize service
	$CPUserService = new UserService();
	$CPUserService->setDBDriverForLiveSystem($live_DB_driver);
	$CPUserService->setDBDriverForCorePlatForm($platform_DB_driver);
	
	$bucket_size = USER_USER_BUCKET_SIZE;
	
	function getLiveUserCount() {
		global $CPUserService;
		$totalUsers = $CPUserService->getNetworkUserCount();
		return $totalUsers[0]["Total"];
	}
	
	function main() {

		global $UserService;
		global $bucket_size;
		$totalUsers = getLiveUserCount();	//from core platform
		$number_of_times_to_execute = getNumberOfTimeToExecute($totalUsers);		
		$cnt = 1;
		
		for($i = 0; $i < $number_of_times_to_execute; $i++) {
			
			$startLine = ($i == 0 ) ? 0 : ($i * $bucket_size);
			$users = getNetworkUsersByBatch($startLine,$bucket_size);
		
			foreach ($users as $user) {
   				
				$hash_user_id = $user["id"]; //shall be used as hash_user_id_from
   				$affinedUsers = getAffinedUsersWtihAffinity($hash_user_id);	//with affinity beyond some threshold level
   				
   				if(isset($affinedUsers) && count($affinedUsers) > 0) {
   					
   					foreach ($affinedUsers as $affinedUser) {
   						$hash_user_id_to = $affinedUser["user_id_to"]; 
   						$cpAffinity = $affinedUser["affinity"];
   						modifyAffinityForAffinedUsers($hash_user_id, $hash_user_id_to,$cpAffinity);
   					}
   				}
				++$cnt;
			}
		}
	}
	
	function getNetworkUsersByBatch($startLine,$bucket_size) {
		global $CPUserService;	
		$data = array ( 
						"startLine" => $startLine,
						"bucket_size" => $bucket_size
					);
		return $CPUserService -> getNetworkUsersByBatch($data);;
	}
	
	function modifyAffinityForAffinedUsers($hash_user_id, $hash_user_id_to,$cpAffinity) {
		global $UserService;	
		$newAffinity = getNewAffinty($hash_user_id, $hash_user_id_to, $cpAffinity);
		$param = array(
						"hash_user_id_from" => $hash_user_id,
						"hash_user_id_to" => $hash_user_id_to,
						"affinity" => $newAffinity
					);
		$data = $UserService -> updateAffinityForAffinedUsers($param);
		return $data;
	}
	
	function getNewAffinty($hash_user_id, $hash_user_id_to,$cpAffinity) {
		/*
		 * logic to count new affinty
		 * for now just returning 2
		 */
		return 0.95;
	}
	
	function getAffinedUsersWtihAffinity($hash_user_id) {
		global $CPUserService;	
		$param = array("hash_user_id_from" => $hash_user_id);
		$data = $CPUserService -> getAffinedUsersWtihAffinity($param);
		return $data;
	}
	
	function getNumberOfTimeToExecute($totalUsers){
		global $bucket_size;
		//number of times to execute = count divided by 300
		$answer = ceil($totalUsers / $bucket_size);
		return $answer;
	}
	
	
	
	main();
?>
