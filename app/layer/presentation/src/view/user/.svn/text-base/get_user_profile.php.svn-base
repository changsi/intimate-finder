<?php
if (!empty($profile_info)) {
	//age
	$html_age = ($profile_info['age'] != 0) ? '<age>' . $profile_info['age'] . '</age>' : '' ;

	//gender
	switch ($profile_info['gender']) {
		case 1:
			$html_gender = '<gender>male</gender>';
			break;
		case 2:
			$html_gender = '<gender>female</gender>';
			break;
		default:
			$html_gender = '';
	}
	
	//education - we choose the highest education followed by the user
	$html_education = (!empty($profile_info['highest_education']) && $profile_info['highest_education'] != 'unknown') ? '<education>' . $profile_info['highest_education'] . '</education>' : '' ;
				
	//relationship status
	$html_relationship = (isset($profile_info['name']) && $profile_info['name'] != 'unknown') ? '<relationship_status>' . $profile_info['name'] . '</relationship_status>' : '' ;
	
	
	echo $PROFILE_URL . '?network_user_id=' . $profile_info["network_user_id"] . '&network_id=' . $profile_info['network_id'].'
	<!DOCTYPE html><html lang="en">
		<body class="article">
			'.$html_age.'
			'.$html_gender.'
			'.$html_education.'
			'.$html_relationship.'
		</body>
	</html>' ;
}

?>
