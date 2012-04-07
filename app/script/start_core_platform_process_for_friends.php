<?php
/*
Gets the friends which were NOT parsed yet (control_flag=0) and creates a file to serve as input to the CP.
Gets the friends from the network_friend talbe (FB DB).
You can just copy the code from the same file from the SPIRAL project.

Uses: Just copy the same file from the SPIRAL project and make the appropriate changes.

INFO : Steps :
 1. Check if there are any CP process running 
 2. If not Get all the friends in FB DB that have been processed (control flag = 1), write to a file and tell the CP to remove these ones from the CP DB
 3. Wait until finish
 4. Get all the friends in FB DB that have not yet been processed (control flag =0) write to a file and run the friend module in CP to insert them in the CP DB
 5. at the end of that script both DB should be updated for the friends

*/

require dirname(__FILE__) . "/common.php";

require getContextFilePath("user.service.UserFriendService");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//initialize service
$userFriendService = new UserFriendService();
$userFriendService->setDBDriverForLiveSystem($live_DB_driver);

$check_process_command = "ps -ef | grep 'java -Dfile.encoding=UTF-8 -classpath " . $CP_JAR_PATH . "' | grep -v \"grep\" | wc -l";
$check_remove_process_command = "ps -ef | grep 'java -Dfile.encoding=UTF-8 -classpath " . $CP_JAR_PATH . "' | grep -v \"grep\" | wc -l";

$process_count = system($check_process_command);	//get process count from `ps` command

if($process_count  == 0) {
	//remove previous friends first
	$remove_start = 0;
	$total_remove_limit = $userFriendService->getProcessedUserIdsCount();
	
	$remove_bucket_start = $remove_start;
	$remove_bucket_limit = $CP_FRIEND_REMOVE_BUCKET_SIZE;
	
	$tick0 = microtime(true) * 10000;	//for new file name
	$remove_flag_filepath = $SAVE_FRIEND_FOLDER_PATH . "remove_flag_friends_" . $tick0;
	$remove_filepath_set = array();

	while($remove_bucket_start < $remove_bucket_limit) {
		$tick = microtime(true) * 10000;	//for new file name
		$remove_filepath = $SAVE_FRIEND_FOLDER_PATH . "remove_friends_" . $tick;
		
		$remove_param = array (
			"start" => $remove_bucket_start,
			"limit" => $remove_bucket_limit,
			"filepath" => $remove_filepath
		);
		
		$remove_filepath_set[] = $remove_filepath;
		
		$userFriendService->saveProcessedUserIdsForCP($remove_param);
		//run remove friend process
		runCorePlatformRemoveFriendModules($remove_filepath);
		
		$remove_bucket_start += $remove_bucket_limit;
	}

	$sleep_time = 15;
	$timeout_counter = 0;
	
	//wait until remove process is finished
	while(true) {
		$remove_process_count = system($check_remove_process_command);
		
		if($remove_process_count == 0) {
			break;
		}
		else if($timeout_counter > $SCRIPT_TIMEOUT) {
			echo "Time out......\n";
			break;
		}
		
		sleep($sleep_time);
		$timeout_counter += $sleep_time;
	}
	
	$remove_flag_param = array (
		"control_flag" => 0,
		"limit" => $total_remove_limit
	);
	
	//update control flag to 0
/*	foreach($remove_filepath_set as $fpath) {
		$cf_param = array (
			"control_flag" => 0,
			"filepath" => $fpath
		);
	
		$userFriendService->updateNetworkFriendControlFlagByMainUserFromFile($cf_param);
	}
*/
	// Delete the Columns from network_friend where control_flag = 2
	foreach($remove_filepath_set as $fpath) {
		$cf_param = array (
			"control_flag" => 2,
			"filepath" => $fpath,
		);
	
		$userFriendService->deleteNetworkFriendControlFlagByMainUserFromFile($cf_param);
	}
	
	//execute insert friend process
	$start = 0;
	$total_limit = $userFriendService->getAllNetworkFriendsMappingCount();	//total number of friends for process

	$bucket_start = $start;	//buckets start index
	$bucket_limit = $CP_FRIEND_BUCKET_SIZE;	//bucket limit
	$filepath_set = array();

	while($bucket_start < $total_limit) {
		echo $bucket_start . ", " . $bucket_limit . "\n";
		$tick = microtime(true) * 10000;	//for new file name
		$filepath = $SAVE_FRIEND_FOLDER_PATH . "friends_" . $tick;
		
		$param = array (
			"start" => $bucket_start,
			"limit" => $bucket_limit,
			"filepath" => $filepath
		);
		
		$filepath_set[] = $filepath;
		
		$userFriendService->saveAllNetworkFriendsMappingForCP($param);
		//run insert friend process
		runCorePlatformFriendModules($filepath);

		$bucket_start += $bucket_limit;
	}
	
	//update control flag to 1
	foreach($filepath_set as $fpath) {
		$cf_param = array (
			"control_flag" => 1,
			"network_id" => 2,
			"filepath" => $fpath
		);
	
		$userFriendService->updateUserNetworkFriendControlFlagFromFile($cf_param);
	}
}

function runCorePlatformFriendModules($input_path) {
	global $CP_JAR_PATH;
	global $CP_RUN_FRIEND_MODULE_PARAM;
	
	$param = sprintf($CP_RUN_FRIEND_MODULE_PARAM, $input_path);
	$size = filesize($input_path);
	$cmd = "nohup java -Dfile.encoding=UTF-8 -classpath " . $CP_JAR_PATH . " " . $param . " 0 " . $size . " &";
	
	//echo "$cmd\n";
	
	popen($cmd, "r");
}

function runCorePlatformRemoveFriendModules($input_path) {
	global $CP_JAR_PATH;
	global $CP_RUN_REMOVE_FRIEND_MODULE_PARAM;
	
	$param = sprintf($CP_RUN_REMOVE_FRIEND_MODULE_PARAM, $input_path);
	$size = filesize($input_path);
	$cmd = "nohup java -Dfile.encoding=UTF-8 -classpath " . $CP_JAR_PATH . " " . $param . " 0 " . $size . " &";
	
	//echo "$cmd\n";
	
	popen($cmd, "r");
}

exit();
?>
