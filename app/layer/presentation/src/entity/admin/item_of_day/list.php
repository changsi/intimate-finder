
<?php
	/**
	 * 	lists all items in all days
		Uses: ItemOfDayService->getAllItemsOfDay(...);
	 */
	$tribes = $TribeService->getAllTribes();
	$date = date("m/d/Y");
	
	if(!isset($_GET["date"])){
		$today = date("m/d/Y");
		$data = $ItemOfDayService->getAllItemsOfDay($today);	
	}
	
	if(isset($_POST["date"]) && isset($_POST["item"]) && isset($_POST["tribe"])) {
	
		$date = $_POST["date"];
		$item_id= $_POST["item"];
		$tribe_id = $_POST["tribe"];
		$object = $item_id;
	
		if(isset($_POST["delFlag"]) && $_POST["delFlag"] == 1){
			$param = array(	
						"tribe_id" => $tribe_id,
						"date" => $date
					);
			$submitFlag = $ItemOfDayService->deleteItemOfDay($param);
			
		} else {
			list($object_id, $object_type_id) = split('[#.-]', $object);
				
			$param = array(	"object_id" => $object_id, 
						"object_type_id" => $object_type_id,
						"tribe_id" => $tribe_id,
						"date"=> $date
					);
			$submitFlag = $ItemOfDayService->insertItemOfDay($param);
		}
	}
?>
