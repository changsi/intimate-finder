<?php

require dirname(__FILE__) . "/common.php";

require getContextFilePath("user.service.UserService");
require getContextFilePath("sn.service.SNFacebookService");
require getContextFilePath("content.service.ContentService");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//initialize service
$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);
$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);
$ContentService = new ContentService();
$ContentService->setDBDriverForLiveSystem($live_DB_driver);

ob_start();
echo "Print started: \n";
if ($argc != 3 || !isset($argv[1]) || !is_numeric($argv[1]) || !isset($argv[2]) || !is_numeric($argv[2])) {
	die("The Parameters is Wrong!");
}

$start = $argv[1];
$limit = $argv[2];

echo "the start is ".$start." and the limit is ".$limit."\n";
$data = array(
	"network_id"=>2, 
	"start" => $start,
	"limit" => $limit
);
$expired_user = $UserService->getExpiredUser($data);
$expired_user_count = count($expired_user);
$numberOfBuckest = floor($expired_user_count/EXPIRED_USER_BUCKETS_SIZE);
$numberOfItemsForLastBuckest = $expired_user_count%EXPIRED_USER_BUCKETS_SIZE;
if ($numberOfBuckest > 0) {
	for ($j = 0; $j < ($numberOfBuckest*EXPIRED_USER_BUCKETS_SIZE); $j = $j + EXPIRED_USER_BUCKETS_SIZE) {
		
	}
}



function updateUserExpiryDate($expiry_date, $user_id){
	global $UserService;
	$data = array(
		"expiry_date" => $expiry_date,
		"network_id"	=> 2,
		"network_user_id" => $user_id	
	);
	$UserService->updateUserExpiryDate($data);
	
}
exit();
?>
