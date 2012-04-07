<?php
/*

*/
	if(isset($_GET["tribe"]) && isset($_GET["date"])) {
		$date = $_GET["date"];
		$tribe_id = $_GET["tribe"];
		$param = array( "day"=> $date,
						"tribe_id"=> $tribe_id			
				);
		$items = $ItemOfDayService->getAllItemsForTribe($param);
	}
	

?>
