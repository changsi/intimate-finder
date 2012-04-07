<?php
/*
Receives a TRIBE ID/MANUAL DP ID and update his meta-data and categories.

Uses: TribeService->updateTribe(...)
*/
$nameCheck = isset($_GET["name"]) ? $_GET["name"] : NULL;
$nameCheck = StringHelper::normalizeString($nameCheck);
//For the form validation (requested by ajax)
if ($nameCheck) {
	$tribe = $TribeService->getTribeByName(array("tribe_name" => $nameCheck));
}
else {
	if (isset($_POST['submit'])) {
		$tribe_data = array();
		//Put all the posts into php vars, and add them to tribe_data arr
		foreach ($_POST as $key => $val) {
			$$key = $val;
		}
		
		$categories = array (
			52909 => $c52909, //arts_entertainment
			53818 => $c53818, //business
			28813 => $c28813, //computer_internet
			48374 => $c48374, //culture_politics
			30399 => $c30399, //gaming
			58954 => $c58954, //health
			47792 => $c47792, //law_crime
			48378 => $c48378, //religion
			52868 => $c52868, //recreation
			49049 => $c49049, //science_technology
			5045  => $c5045, //sports
			44675 => $c44675, //weather
		);
		foreach ($categories as $key => $affinity) {
			if (!$affinity) {//if affinity == 0
				unset($categories[$key]);
			}
		}
		$profile_categories = array();
		//gender
		if (is_array($gender)) {
			if (count($gender) > 1) { //male and female
				$profile_categories[9995999] = 1;
				$profile_categories[9996999] = 1;
			}
			else if ($gender[0] == 9995999) {
				$profile_categories[9995999] = 1;//male only
			}
			else if ($gender[0] == 9996999) {
				$profile_categories[9996999] = 1;//female only
			}
		}
		
		//age
		if (is_array($age)) {
			$cAge = count($age) ;
			foreach ($age as $a) {
				$profile_categories[$a] = 1;
			}
		}
		
		//education
		if (is_array($education)) {
			foreach ($education as $e) {
				$profile_categories[$e] = 1;
			}
		}
		
		//relation ship status
		if (is_array($relation)) {
			foreach ($relation as $r) {
				$profile_categories[$r] = 1;
			}
		}
		
		
		$tribe_data["categories"] = $categories;
		$tribe_data["profile_categories"] = $profile_categories;
		$tribe_data["name"] = StringHelper::normalizeString($name);
		$tribe_data["description"] = $description;
		$tribe_data["slogan"] = $slogan;
		$tribe_data["badge"] = $badge;
		
		if (isset($tid) && $tid) {//Updating the tribe info i.e we do not change its id just update all the fields
			$tribe_data['dp_id'] = $tid;			
			echo "updating";
			$tribeId = $TribeService->updateTribe($tribe_data);
		}
		else {//We insert a new tribe in database creating a new id.
			echo "inserting";
			$tribeId = $TribeService->insertTribe($tribe_data);
		}
		
		header("Location: ".HOST_PREFIX."/admin/tribe/list");

	}



	$tribeId = isset($_GET["tid"]) ? $_GET["tid"] : NULL;

	$data = array();

	if ($tribeId) {
		$tribeInfo = $TribeService->getTribeInfoById(array("tribe_id" => $tribeId));
		$tribeCategories = $TribeService->getTribeCategories(array("tribe_id" => $tribeId));
		if (!empty($tribeInfo)) {
			$data = $tribeInfo[0];
		}
		$categories = array();
		//replacing the index by the id of the categories, more suitable for display
		foreach ($tribeCategories as $cat) {
			$categories['c'.$cat['category_id']] = $cat;
		}

		if (!empty($tribeCategories)) {
			$data["cat"] = $categories;
		}

		$data["tid"] = $tribeId;
	}
}
?>
