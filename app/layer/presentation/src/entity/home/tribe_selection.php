<?php

if (isset($_SESSION['tribes']) && $_SESSION["network_user_id"] && $_SESSION["network_id"] && $_SESSION["access_token"]) {
	if (isset($_SESSION['tribe'])) {//check if the user has already a tribe meaning he doesnt belong here..
		header("Location: ".HOST_PREFIX."/home/home");
	}
	else {
	
		$matchingTribes = $_SESSION['tribes'];
		$network_user_id = $_SESSION["network_user_id"];
		$network_id = $_SESSION["network_id"];
		
		if (isset($_POST) && isset($_POST['submit'])) {
			if (isset($_POST['tribe_id'])) {
				$chosenTribe = $_POST['tribe_id'];
				
				//form an array with the tribes ids
				$tribesIds = array ();
				foreach ($matchingTribes as $tribe) {
					$tribesIds[] = $tribe['dp_id'];
				}
				
				//check if that tribe matches one of the tribes for the user--security
				if (in_array($chosenTribe, $tribesIds)) {
					//save the user_tribe relation in the db
					$data['tribe_id'] = $chosenTribe;
					$data['network_user_id'] = $network_user_id;
					$data['network_id'] = $network_id;
					
					$TribeService->saveUserTribe($data);
					
					//save the new tribe in session
					$_SESSION['tribe'] = $chosenTribe;
					header("Location: ".HOST_PREFIX."/home/home");
				}
			}
		}
		else {
			//get info for the matching tribes
			$tribes = array();
			$tribesIds = array();
			foreach ($matchingTribes as $key => $tribe) {
				$tribes[$key]['tribe_id'] = $tribe['dp_id'];
				$tribesIds[] = $tribe['dp_id'];
				
				$param = array (
					"tribe_id" => $tribe['dp_id'],
					"network_user_id" => $network_user_id,
					"network_id" => $network_id
				);
				//info of the tribe
				$data = $TribeService->getTribeInfoById($param);
				$tribes[$key]['info'] = $data[0];
				$data = '';
				
				//affinity of the user with that tribe
				$tribes[$key]['affinity'] = $tribe['affinity'];
				
				//friends of the user in that tribe
				$tribes[$key]['friends'] = $TribeService->getUserFriendsInThatTribe($param);
				$friends = array();
				foreach ($tribes[$key]['friends'] as $friend) {
					$friends[] = $friend['name']; 
				}
				if (!empty($friends)) {
					$friendsStr = implode(",", $friends);
					$tribes[$key]['friends_list'] = $friendsStr;
				}
				$data = '';
				
				
				//hot topics
				//to be defined
			}
			
			//tribes for the more button
			$info = array (
				"tribes" => $tribesIds,
				"network_user_id" => $network_user_id,
				"network_id" => $network_id
			);
			$otherTribes = $TribeService->getOtherTribesInfoAndFriendsInside($info);
			
		
			$tribes = array_merge($tribes, $otherTribes);

		}
	}
}
else {
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
}

?>
