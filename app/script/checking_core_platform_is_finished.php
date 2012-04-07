<?php
/*
Checks whether the core platform has finished for user change the notified column in user_network table
Sends an email or msg to the user after CP finishes
*/
require dirname(__FILE__) . "/common.php";
require getContextFilePath("user.service.UserService");

$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

$platform_DB_driver = new MySqlDB();
$platform_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["PLATFORM_DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$platform_DB_driver->setCharset($DB_CONFIG["ENCODING"]);



$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);
$UserService->setDBDriverForCorePlatform($platform_DB_driver);

$param = array(
	'invitation_pending' => 1,
	'network_id' => 2
	);
$start = 0;
$total_limit = $UserService->getCountInvitationPendingUser($param);
$bucket_start = $start;
$bucket_limit = $CP_URL_BUCKET_SIZE;

while($bucket_start < $total_limit) {
	echo $bucket_start . ", " . $bucket_limit . "\n";
	$param = array (
		"start" => $bucket_start,
		"limit" => $bucket_limit,
		'invitation_pending' => 1,
		'network_id' => 2
	);
		
	$invitation_pending_users = $UserService->getInvitationPendingUser($param);
	if(!empty($invitation_pending_users)) {
		foreach($invitation_pending_users as $invitation_pending_user) {
			//print_r($invitation_pending_user);die();
			$param = array (
				'user_id' => $invitation_pending_user['hash_user_id'],
				'dp_type_id' => 2
			);
			$finished_users = $UserService->getDPsForLiveSystem($param);
		}
	}
	if(!empty($finished_users)) {
		foreach($finished_users as $finished_user) {
			/*$email = $UserService->getEmail($finished_user);
			//$to = "recipient@example.com";
 			//echo $email;die();
 			$header = "";
 			$header .= "Reply-To: envio@localhost.localdomain\r\n"; 
    			$header .= "Return-Path: envio@localhost.localdomain\r\n"; 
    			$header .= "From: envio@localhost.localdomain\r\n"; 
   	 		$header .= "Organization: My Organization\r\n"; 
    			$header .= "Content-Type: text/plain\r\n"; 
     		$subject = "Hi!";
 			$body = "Hey U can now use the app,\n\n By clicking on the link below\nhttp://apps.facebook.com/skyweaver_test";
 			if (mail($email, $subject, $body, $header)) {
   				echo("<p>Message successfully sent!</p>\n\n");
  			} 
  			else {
   				echo("<p>Message delivery failed...</p>\n\n");
  			}*/
  			$param = array(
  				'invitation_pending' => 0,
  				'hash_user_id' => $finished_user['user_id']
  			);
  			
			$UserService->updatePendingInvitation($param);
		}
	}
	
	
	$bucket_start += $bucket_limit;

}
