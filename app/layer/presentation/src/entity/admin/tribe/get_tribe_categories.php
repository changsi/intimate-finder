<?php
/*
Receives a tribe ID via GET and returns the categories for this specific tribe. The tribe's categories are in the tribe_category table (Live DB).
The result shold be encoded in JSON.

Uses: TribeService->getTribeCategories(...)
*/

$tid = isset($_GET['tid']) && $_GET['tid'] ? $_GET['tid'] : false ;

if ($tid) {
	$data = array (
		"tribe_id" => $tid,
	);
	$categories = $TribeService->getTribeCategories($data);
}
else {
 //TODO file error
}


?>
