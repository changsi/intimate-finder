<?php
/*
Form to insert a new manual dp and his correspondent categories.

Uses: TribeService->insertTribe(...)
*/

/********** DEPRECATED - SEE /UPDATE ENTITY **********/
/*
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

	//gender
	if (count($gender) > 0) {
		if (count($gender) > 1) { //male and female
			$categories[9995999] = 1;
			$categories[9996999] = 1;
		}
		else if ($gender[0] == 1) {
			$categories[9995999] = 1;//male only
		}
		else if ($gender[0] == 2) {
			$categories[9996999] = 1;//female only
		}
	}
	//age
	$cAge = count($age) ;
	if ($cAge > 0) {
		if ($cAge == 1) {
			if ($age[0]) {
				$categories[$age[0]] = 1;
			}
		}
		else {
			foreach ($age as $a) {
				$categories[$a] = 1;
			}
		}
	}
	
	//education
	$cEducation = count($education) ;
	if ($cEducation > 0) {
		if ($cEducation == 1) {
			if ($education[0]) {
				$categories[$education[0]] = 1;
			}
		}
		else {
			foreach ($education as $e) {
				$categories[$e] = 1;
			}
		}
	}
	
	//relation ship status
	$cRelation = count($relation) ;
	if ($cRelation > 0) {
		if ($cRelation == 1) {
			if ($relation[0]) {
				$categories[$relation[0]] = 1;
			}
		}
		else {
			foreach ($relation as $r) {
				$categories[$r] = 1;
			}
		}
	}
	
	
	$tribe_data["categories"] = $categories;
	$tribe_data["name"] = $name;
	$tribe_data["description"] = $description;
	$tribe_data["slogan"] = $slogan;
	$tribe_data["badge"] = $badge;
	
	echo "<pre>";
	print_r($tribe_data);
	echo "</pre>";
	$tribeId = $TribeService->insertTribe($tribe_data);
}
*/
?>
