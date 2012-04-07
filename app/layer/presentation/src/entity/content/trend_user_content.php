<?php
/*
Gets the recommended user content from the recommended_user_object table (FB DB) and return the result in a JSON format.

Uses: ContentService->getRecommendedUserContent(...);
*/

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false ;
$network_id = 2;//(Facebook)

$recommendations = array();

if ($uid) {
	$userInfo = array (
		"network_user_id" => $uid,
		"network_id" => $network_id //(Facebook)
	);
	$recommendations = $ContentService->getRecommendedUserContent($userInfo);
}
else {
 //TODO file error
}


?>
