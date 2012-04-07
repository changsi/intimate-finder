<?php
/*
Receives a TRIBE ID/MANUAL DP ID and get all the related DPs based in a affinity from the dp_dp table (CP DB).

Uses: TribeService->getTribeDPs(...)
*/


$tid = isset($_GET['tid']) && $_GET['tid'] ? $_GET['tid'] : false ;

$dynamic_dps = array();

if ($tid) {
	$data = array (
		"tribe_id" => $tid,
		"min_affinity" => MIN_AFFINITY_TRIBE_DP
	);
	$dynamic_dps = $TribeService->getTribeDPs($data);
}
else {
 //TODO file error
}
?>
