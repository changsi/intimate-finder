<?php
/*form which inserts an item of the day
Uses: ItemOfDayService->insertItemOfDay(...); */
$data = $TribeService->getAllTribes();
$submitFlag;
$submitFlag1;
if(isset($_POST["group1"])&&isset($_POST["date"])&&isset($_POST["item"])&&isset($_POST["tribe"])) {
	
	$option = $_POST["group1"];
	if($option==0) {
			$date = $_POST["date"];
			$item_id= $_POST["item"];
			$tribe_id = $_POST["tribe"];
			$object = $item_id;
			
			list($object_id, $object_type_id) = split('[#.-]', $object);
			
			$param = array( "object_id" => $object_id, 
							"object_type_id"=>$object_type_id,
							"tribe_id"=>$tribe_id,
							"date"=>$date
						);
			
			//echo "Object Type: ".	$object_type_id."\n";
			//echo "Object : ".	$object_id."\n";
			//echo "Tribe Id: ".	$tribe_id."\n";		
						
			$submitFlag = $ItemOfDayService->insertItemOfDay($param);
	} else {
			$name = $_POST["name"];
			$url= $_POST["url"];
			$author = $_POST["author"];
			$genre = $_POST["genre"];
			$genre2 = $_POST["genre2"];
			$object_type_id = 9;
			$param = array(
							"name" => $name,
							"url" => $url,
							"author" => $author,
							"genre" => $genre,
							"genre2" => $genre2,
							"object_type_id"=>$object_type_id, 
							"network_object_id" => 0
			
					);
			$submitFlag1 = $ContentService->insertManualObject($param);
		}
}
?>
