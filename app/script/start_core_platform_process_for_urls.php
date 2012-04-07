<?php
/*
Gets the urls which were NOT parsed yet (control_flag=0) and creates a file to serve as input to the CP.
Gets the urls from the network_post_url table (FB DB).
You can just copy the code from the same file from the SPIRAL project.

Uses: Just copy the same file from the SPIRAL project and make the appropriate changes.
*/
require dirname(__FILE__) . "/common.php";

require getContextFilePath("sn.service.FacebookService");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//initialize service
$FacebookService = new FacebookService();
$FacebookService->setDBDriverForLiveSystem($live_DB_driver);


$start = 0;
$total_limit = $FacebookService->getUserUrlsCountForCP();	//total number of urls for process	
//$total_limit = 10;	//for testing

$bucket_start = $start;	//buckets start index
$bucket_limit = $CP_URL_BUCKET_SIZE;	//bucket limit
//$bucket_limit = 10;	//for testing
$filepath_set = array();

while($bucket_start < $total_limit) {
	echo $bucket_start . ", " . $bucket_limit . "\n";
	$tick = microtime(true) * 10000;	//for new file name
	$filepath = $SAVE_URL_FOLDER_PATH . "facebook_" . $tick;
	
	$param = array (
		"start" => $bucket_start,
		"limit" => $bucket_limit,
		"filepath" => $filepath
	);
	
	$filepath_set[] = $filepath;
	
	$FacebookService->saveUrlDataForCP($param);
	
	//DEPRECATED
	//runCorePlatformModules($filepath);

	$bucket_start += $bucket_limit;
}

//update control flag to 1
foreach($filepath_set as $fpath) {
	$cf_param = array (
		"control_flag" => 1,
		"filepath" => $fpath
	);

	$FacebookService->updateNetworkPostUrlControlFlagFromFile($cf_param);
}




/* DEPRECATED - The user history module is prepared to run in cronjob, in that case we just need to copy the file in the appropriate folder and the module will run by itself, no need to execute it
function runCorePlatformModules($input_path) {	
	global $CP_JAR_PATH;
	global $CP_RUN_ALL_MODULES_PARAM;
	global $CP_RUN_USER_HISTORY_PARSER_MODULE_PARAM;
	
	//$param = sprintf($CP_RUN_ALL_MODULES_PARAM, $input_path);
	$param = sprintf($CP_RUN_USER_HISTORY_PARSER_MODULE_PARAM, $input_path);
	$size = filesize($input_path);
	$cmd = "nohup java -Xms1024m -Xmx1024m -Dfile.encoding=UTF-8 -classpath " . $CP_JAR_PATH . " " . $param . " 0 " . $size . " &";
	
	echo "$cmd\n";
	
	popen($cmd, "r");
}
*/
exit();
?>

