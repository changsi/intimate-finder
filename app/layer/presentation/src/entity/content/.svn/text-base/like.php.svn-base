<?php
//Like a content.
//Everytime an user likes something, we should save this action to the user_action talbe (FB DB)
//FUTURE Everytime an user likes something, we should publish his action to his facebook wall.

//Uses: ContentService->likeContent(...);

$network_id = 2; //(Facebook)

$user_network_id = isset($_GET['network_user_id']) && $_GET['network_user_id'] ? $_GET['network_user_id'] : false;
$object_type_id = isset($_GET['object_type_id']) && $_GET['object_type_id'] ? $_GET['object_type_id'] : false;
$object_id = isset($_GET['object_id']) && $_GET['object_id'] ? $_GET['object_id'] : false;
$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : false;
$data = array("network_user_id"=>$user_network_id , "object_type_id"=>$object_type_id , "object_id"=>$object_id, "network_id"=>$network_id, "action_type_id"=>'1');

$object = $ContentService->getObjectByID(array("object_id"=>$object_id, "object_type_id"=>$object_type_id));

//1 means unlike 0 means like
if ($type == '1') {
	$count = $ContentService->unlikeContent($data);
	//$SNFacebookService->getUserId();
	//$SNFacebookService->publishPostInUserWall(array("message"=>"the user unlike ".$object["name"]."!"));
}
else{
	$count = $ContentService->likeContent($data);
	$userid = $SNFacebookService->getUserId();
	$user_name = $SNFacebookService->getUserName(array("network_user_id"=>$userid));
	try{
		$SNFacebookService->publishPostInUserWall(array("network_user_id"=>$userid,"message"=>$user_name." likes ".$object["name"].".", "link"=>$object['url']));
	}catch (Exception $e) {
    
	}
	
}
$status = $ContentService->checkLikeStatus($data);
			


?>
