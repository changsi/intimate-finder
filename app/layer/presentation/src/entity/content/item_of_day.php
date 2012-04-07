
<?php
/*Returns the item of the day in a JSON format.
Get this item from the item_of_day table (FB DB)

Uses: ItemOfDayService->getItemOfDay(...);*/

/*$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$login_user_id = isset($_SESSION["network_user_id"]) ? $_SESSION["network_user_id"] : NULL;

if (!isset($access_token) || !isset($login_user_id)) {
    header("Location: ".HOST_PREFIX."/sn/facebook/login");
    die();
}*/
	
	//this is for the first time this page gets load, and takes the item for today
	if(!isset($_GET["date"])){
		$today = date("m/d/Y");
		$data = $ItemOfDayService->getAllItemsOfDay($today);		
	} 
	
?>
