<?php
require "module/AlchemyAPI.php";

function getCategoryForUrl($url) {
	$api_key_path = "/home/envio/projects/targeted_advertising/trunk/client/spiral/app/lib/web/AlchemyAPI/example/api_key.txt";
	$result_array = array();
	
	try {
		$alchemyObj = new AlchemyAPI();
		$alchemyObj->loadAPIKey($api_key_path);
		$category = $alchemyObj->URLGetCategory($url);
		$category_array = json_decode(json_encode((array) simplexml_load_string($category)),1);
		
		if(!empty($category_array)) {	
			$result_array["category"] = $category_array["category"];
			$result_array["confidence"] = $category_array["score"];
		}
	}
	catch(Exception $e) {
	}
	
	return $result_array;
}

?>
