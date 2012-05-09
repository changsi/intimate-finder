<?php
$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : NULL;

if($user_id) {
	$login = true;
		
}
else{
	$login = false;
}



?>