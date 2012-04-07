<?php
/*
Receives a DP ID via GET and returns the categories for this specific DP. The dp's categories are in the dp_category table (CP DB).
The result shold be encoded in JSON.

Uses: DPCategoryService->getDPCategories(...)
*/

$dpid = isset($_GET['dpid']) && $_GET['dpid'] ? $_GET['dpid'] : false ;

$dynamic_dps = array();

if ($dpid) {
	$data = array (
		"dp_id" => $dpid,
	);
	$categories = $DPCategoryService->getDPCategories($data);
}
else {
 //TODO file error
}


?>
