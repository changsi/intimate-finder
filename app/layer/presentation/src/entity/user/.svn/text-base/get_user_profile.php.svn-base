<?php
/*
Receives an USER ID and a NETWORK_ID and returns the user network data from the user_network_data table.
The data should be return in a HTML format. => This would be used by the CP in the profile parser.

Uses: UserService->getUserNetworkProfile(...)
*/


$network_user_id = isset($_GET['network_user_id']) && $_GET['network_user_id'] ? $_GET['network_user_id'] : false;
$network_id = isset($_GET['network_id']) && $_GET['network_id'] ? $_GET['network_id'] : false;

$profile_info = '';

if ($network_user_id && $network_id) {
	$PROFILE_URL = LOCAL_HOST . '/user/get_user_profile' ;
	$user_param = array (
		"network_user_id" => $network_user_id,
		"network_id" => $network_id 
	);
	
	$user_profile = $UserService->getUserNetworkProfile($user_param);
	
	if (!empty($user_profile)) {
		//createProfileCrawlerHtml($user_profile[0]);
		$profile_info = $user_profile[0];
	}
}


/* DEPRECATED - the view is used instead
function createProfileCrawlerHtml($profile_info) {
	global $PROFILE_URL;
	
	$html_str = '';
	$html_str .= $PROFILE_URL . '?user_network_id=' . $profile_info["network_user_id"] . '&network_id=' . $profile_info['network_id'] ;
	$html_str .= '<!DOCTYPE html><html lang="en"> \n';
	$html_str .= '<body class="article"> \n';

	//age
	$html_str .= ($profile_info['age'] != 0) ? '<age>' . $profile_info['age'] . '</age> \n' : '' ;
	
	//gender
	switch ($profile_info['gender']) {
		case 1:
			$html_str .= '<gender>Male</gender> \n';
		break;
		case 2:
			$html_str .= '<gender>Female</gender> \n';
		break;
	}
	
	//education - we choose the highest education followed by the user
	$html_str .= (!empty($profile_info['highest_education'])) ? '<education>' . $profile_info['highest_education'] . '</education> \n' : '' ;
	
	//relationship status
	$html_str .= (isset($profile_info['relationship'])) ? '<relationship_status>' . $profile_info['relationship'] . '</relationship_status> \n' : '' ;
	
	$html_str .= '</body> \n' ;
	$html_str .= '</html>' ;
	
	echo $html_str;
}
*/

?>
