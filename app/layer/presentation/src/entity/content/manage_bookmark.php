<?php
/*
	i: Tables:
		BookMark: 
			network_user_id,network_id, object_type_id, object_id, createddate
			
	ii: Queries:
		insert bookmark
		select bookmark
		delete bookmark
	
	1> rules are written for above queries for above mentioned things in bookmarkrule
	
	2> call them from content_service
	
	3> manage_boomark will receive following from Ajax request
		i: event code (for insert, update, or delete)
		ii: network_user_id,network_id, object_type_id, object_id
	
	4> 1 for select, 2 for insert, 3 for delete
	
	
	its up to fornt end how they want to make a request
*/
	
	$data = array();
	$result = false;
	//echo "var data = " . json_encode($data) . ";";
	
	if(isset($_GET["network_user_id"]) && isset($_GET["network_id"]) && isset($_GET["event_id"])) {
		$event_id	= $_GET["event_id"];
		if($event_id<4){
			
			$network_user_id = $_GET["network_user_id"];
			$network_id	= $_GET["network_id"];
			
			$data["network_id"] =  $network_id  ;
			$data["network_user_id"] = $network_user_id;
			
			if($event_id != 1) { //1 is case of select, if it is not the case we need  object_type_id and object_id
				
				if(isset($_GET["object_type_id"]) && isset($_GET["object_id"])) {
					
					$object_id = $_GET["object_id"];
					$object_type_id	= $_GET["object_type_id"];
					
					$data["object_type_id"] = $object_type_id ;
					$data["object_id"] = $object_id ;
				
					if($event_id == 2 )	{
						$data["object_ids"] = $object_id ;
						if(count($ContentService->getObjectByIDs($data))>0){
							$result = $ContentService->insertBookMark($data);  //1 : insert
						}
						
					}
					
					if($event_id == 3){
						$result = $ContentService->deleteBookMark($data);	//3 : delete
					}
					
					echo "var data = " . json_encode($result) . ";";
					die();
				}
			
			} else {
				$result = $ContentService->getBookMark($data);	//3 : select
				if(isset($result)) {
					echo "var data = " . json_encode($result) . ";";
					die();
				}
			}
		}
		
		
		
		
		
	}
?>
