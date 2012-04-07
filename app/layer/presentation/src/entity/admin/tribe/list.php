<?php
/*
List all the Manual DPs

Uses: TribeService->getAllTribes(...)
*/

$tribeId = isset($_GET["tid"]) ? $_GET["tid"] : NULL;

if ($tribeId) { //if tribe the user clicked on get tribe info button
	$tribe = $TribeService->getTribeInfoById(array("tribe_id" => $tribeId));
	if (!empty($tribe)) {
		$data = $tribe;
	}
}
else {
	$tribes = $TribeService->getAllTribes();
}


?>
