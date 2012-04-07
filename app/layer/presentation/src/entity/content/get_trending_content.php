<?php
/*
Gets the recommended user content from the recommended_user_object table (FB DB) and return the result in a JSON format.
	1> get top 100 affined users
	2> get their books with count i.e. frequency
	3> update the frequency
		3.1> get other users with affinty greater some threshold
		3.2> get the friend of the user
		3.3> get their recency factor
	4> calculate tredning score of each book
	5> sort according to trending score
	
*/
	if(isset($_SESSION["userId"])) $userId = $_SESSION["userId"];
	else $userId = "550022193";
	
	if(isset($_SESSION["network_id"])) $network_id = $_SESSION["network_id"];
	else $network_id = "2";
	
	$object_types = $ContentService->getContentTypes();
	
	if(isset($_POST["network_user_id"]) && isset($_POST["object_type_id"]) && isset($_POST["network_id"])) {
	
		$network_user_id = $_POST["network_user_id"];
		$network_id	= $_POST["network_id"];
		$hash = IdService::getUserId($network_id.$network_user_id);
		$hash_user_id_from = $hash;
		$object_type_id = $_POST["object_type_id"];
		
		if($hash_user_id_from != "") {
			$topAffinedUsers = getTopAffinedUsers($hash_user_id_from);
			
			
			if(isset($topAffinedUsers) && count($topAffinedUsers) > 0) {
				
				$topAffinedUsers = getStringFromArray($topAffinedUsers);
				
				if($object_type_id != 10 ) $userObjects = getObjectsOfUsersWithCount($topAffinedUsers,$object_type_id);	//1
				else $userObjects = getPostsOfUsersWithCount($topAffinedUsers);
				
//				echo "<pre>";
//					print_r($userObjects);
//				echo "</pre>";
				
				
				$userFriends = getUserFriends($network_user_id, $network_id);
				$userFriends = collectElementsFromAssocitativeArray($userFriends,"network_user_id_to");
				
				$thresholdAffinedUsers = getUsersWithThresholdAffinty($hash_user_id_from);
				$thresholdAffinedUsers = collectElementsFromAssocitativeArray($thresholdAffinedUsers,"network_user_id");

				$modifiedUserObjects = modifyObjects($userObjects,$userFriends,$thresholdAffinedUsers); // change frequency and get Recency Factor
				
				
				
				$objects = groupUserObjects($modifiedUserObjects);	// by same objectid with taking median of RF and MAX of frequency
//				echo "<pre>";
//					print_r($objects);
//				echo "</pre>";
				
				$trendingObjectIds = getTrendingObjectIds($objects);
			
				$trendingObjects = getObjectByIDs($trendingObjectIds);
				
			
				
			}
		}
		
	}
	
	function getObjectByIDs($trendingObjectIds) {
		global $object_type_id;
		global $ContentService;
		
		$data = array();
		$result = array();
		
		foreach($trendingObjectIds as $key=>$value){
			if(isset($data["object_ids"])){
				$data["object_ids"].= ",".$key ;
			}
			else $data["object_ids"] = $key;
			$result[$key] = array();
		}
	
		
		if(isset($data["object_ids"]) && $data["object_ids"]!="") {
			$data["object_type_id"] = $object_type_id;
			if($object_type_id != 10 ) $objectByIDs = $ContentService->getObjectByIDs($data);
			else $objectByIDs =  $ContentService->getUrlByIDs($data);
			foreach($objectByIDs as $objectByID) {
				$objectId = $objectByID["object_id"];
				$result[$objectId]["name"] = $objectByID["name"];
				$result[$objectId]["url"] = $objectByID["url"];
			}
		}
		
//		echo "<pre>";
//					print_r($result);
//		echo "</pre>";

		return $result;
	}
	
	
	/*
	 *	Change frequency and get Recency Factor
	 */
	function modifyObjects($userObjects,$userFriends,$thresholdAffinedUsers) {
		global $UserService;
		global $object_type_id;
		$newUserObjects = $userObjects;
		$objectToFrequency =  array();
		foreach ($newUserObjects as &$object) {
			$network_user_id = $object["network_user_id"];
			$object_id = $object["object_id"];
			$frequency	= $object["total"];
			$delta_hour = $object["DELTA_Hour"];
			$delta_day = $object["DELTA_Day"];
			
			if( in_array($network_user_id, $userFriends) || in_array($network_user_id, $thresholdAffinedUsers["network_user_id_to"]) ) {
				
				$objectToFrequency[$object_id] = (isset($objectToFrequency[$object_id]))? $objectToFrequency[$object_id]+1:++$frequency;
				$object["total"] = $objectToFrequency[$object_id];
			}
			
			$object["RF"] = getRFFromDelta($object_id,$delta_day,$delta_hour,$object_type_id); //Recency Factor
			//echo("=>".$object["object_id"]." ".$object["RF"] );
		}
		
	
		return $newUserObjects;
		
	}

	function getTopAffinedUsers($hash_user_id_from) {
		global $UserService;
		$topUser = 5; //later from config
		$data = array("hash_user_id_from" => $hash_user_id_from, 
						"limit"=> $topUser);
		
		$result = $UserService->getTopAffinedUsers($data); 
		return $result;
	}
	
	function getObjectsOfUsersWithCount($topAffinedUsers, $object_type_id) {
		global $UserService;
		$limit = 1000; // later from config file
		
		$data = array ("network_user_ids" => $topAffinedUsers, 
						"object_type_id" => $object_type_id,
						"limit" => $limit
						);
		
		$result = $UserService->getObjectsOfUsersWithCount($data);
		return $result;
	}
	
	function getPostsOfUsersWithCount($topAffinedUsers) {
		global $UserService;
		$limit = 1000; // later from config file
		
		$data = array ("network_user_ids" => $topAffinedUsers, 
						"limit" => $limit
						);
		
		$result = $UserService->getPostsOfUsersWithCount($data);
		return $result;
	}
	
	function getUserFriends($network_user_id, $network_id) {
		global $UserService;
		
		$data = array ("network_id" => $network_id, "user_id_from" =>$network_user_id);
		$result = $UserService->getLiveUserFriends($data);
		return $result;
	}
	
	function getUsersWithThresholdAffinty($hash_user_id_from) {
		global $UserService;
		$threshold = 0.75; //later from config
		$data = array ("hash_user_id_from" => $hash_user_id_from, "threshold" => $threshold);
		$result = $UserService->getUsersWithThresholdAffinty($data);
		return $result;
	}
	
	
	function groupUserObjects($modifiedUserObjects) {
		global $UserService;
		$groupedObjects =  array();
	
		foreach ($modifiedUserObjects as $userObject) {
		
			$object_id = $userObject["object_id"];
			
			if(!isset($groupedObjects[$object_id])){
				$groupedObjects[$object_id] = array();
				$groupedObjects[$object_id]["RFs"] =array();
			}
			
			array_push($groupedObjects[$object_id]["RFs"], (int) $userObject["RF"]);
			$groupedObjects[$object_id]["total"] =  $userObject["total"];
			
		}
		
		//we are done with grouping now take median of Rfs
		
		foreach ($groupedObjects as &$groupedObject) {
			$RFs = $groupedObject["RFs"];
			 if(!is_array($RFs)) {
			 	echo true;
			 }
			
			$groupedObject["RFmedian"] = median($RFs);
		
			$frequency =  $groupedObject["total"];
			$recency =  $groupedObject["RFmedian"] ;
			
			$groupedObject["TS"] =  round( ($frequency*100000) / ($recency==0?1:$recency),4);
			
//			echo "<pre>";
//					print_r($groupedObject);
//			echo "</pre>";
		}
		
		
		return $groupedObjects;
	}
	
	function median($args)
	{
       switch(count($args))
	    {
	        case 0:
	            trigger_error('median() requires at least one parameter',E_USER_WARNING);
	            return false;
	            break;
	
	        case 1:
	            $args = array(array_pop($args));
	            // fallthrough
	
	        default:
	            if(!is_array($args)) {
	                trigger_error('median() requires a list of numbers to operate on or an array of numbers',E_USER_NOTICE);
	                return false;
	            }
	
	            sort($args);
	            
	            $n = count($args);
	            $h = intval($n / 2);
	
	            if($n % 2 == 0) { 
	                $median = ($args[$h] + $args[$h-1]) / 2; 
	            } else { 
	                $median = $args[$h]; 
	            }
	
	            break;
	    }
	    
    	return $median;
	}
	
	function getTrendingObjectIds($objects) {
		
		$ts = array();
		
		foreach ($objects as $key => $row) {
    		$ts[$key] = $row['TS'];
		}
		array_multisort($ts, SORT_DESC, $objects);
		return $ts;
	}
	
	function getStringFromArray($topAffinedUsers) {
		$result = "";
		foreach ($topAffinedUsers as $topAffinedUser) {
			if($result!="")
    			$result .= ",".$topAffinedUser["network_user_id"];
    		else {
    			
    			$result .= $topAffinedUser["network_user_id"];
    		}
		}
		return $result;
	}
	
	function collectElementsFromAssocitativeArray($userFriends, $keyname) {
		$result =  array();
		foreach ($userFriends as $userFriend) {
			array_push($result, $userFriend[$keyname]);
		}
		return $result;
	}
	
	function getRFFromDelta($object_id,$delta_day,$delta_hour,$object_type_id) {
		$month = ceil($delta_day / 30);
		$week = ceil($delta_day / 7);
		$result;
		
		
		if($object_type_id == 2) {
			 $result = pow(2,($month-1)); 
		} 
		else if($object_type_id == 10) {
			 $result = pow(2,($delta_day-1)); 
		}
		else {
			$result = pow(2,($week-1)); 
		}
		
		
		return $result;
	}
	
?>
