<?php
/*
Gets all the users from the user_network_data table with control flag 0 and create a file similar with the following one:
network_id.network_user_id	user_profile_url timestamp (must be 0000-00-00 00:00:00)
2123123123209688	http://localhost/facebook_app/app/user/get_user_profile.php?network_user_id=123123123209688&network_id=2	0000-00-00 00:00:00
xxxx	http://localhost/facebook_app/app/user/get_user_profile.php?network_user_id=....&&network_id=2	0000-00-00 00:00:00
...
Then copy this file to the user_history module folder from the CP.

Uses: UserService->getUserNetworkProfile(...) // replaced by getAllNetworkUsersProfilesWithControlFlag
*/

require dirname(__FILE__) . "/common.php";

$NETWORK_ID = 2 ;//Facebook
$PROFILE_URL = LOCAL_HOST . '/user/get_user_profile' ;

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

require getContextFilePath("user.service.UserService");


//initialize service
$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);



$start = 0;
$data = array(
	"network_id" => $NETWORK_ID
);
$total_limit = $UserService->getCountOfAllNetworkUsersProfilesWithControlFlag($data);	//total number of users to process	
//$total_limit = 10;	//for testing

$bucket_start = $start;	//buckets start index
$bucket_limit = $CP_URL_BUCKET_SIZE;	//bucket limit
//$bucket_limit = 10;	//for testing
$filepath_set = array();

while($bucket_start < $total_limit) {
	echo $bucket_start . ", " . $bucket_limit . "\n";
	$tick = microtime(true) * 10000;	//for new file name
	$filepath = $SAVE_FOLDER_PATH . "facebook_user_network_data_" . $tick;
	
	$param = array (
		"start" => $bucket_start,
		"limit" => $bucket_limit,
		"filepath" => $filepath,
		"profile_url" => $PROFILE_URL,
		"network_id" => $NETWORK_ID 
	);
	
	$filepath_set[] = $filepath;
	
	$UserService->saveUserDataForCP($param);
	
	$bucket_start += $bucket_limit;
}

//update control flag to 1
foreach($filepath_set as $fpath) {
	$cf_param = array (
		"control_flag" => 1,
		"filepath" => $fpath,
		"network_id" => $NETWORK_ID 
	);

	$UserService->updateUserNetworkDataControlFlagFromFile($cf_param);
}



?>
