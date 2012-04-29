<?php 

function get_data_from_profile($user_profile){
	if(isset($user_profile['birthday'])){
		$birthday= date_parse($user_profile['birthday']);
		$birth_date = $birthday['year'].'-'.$birthday['month'].'-'.$birthday['day'];
	}else{
		$birth_date = '';
	}
	if(isset($user_profile['gender'])){
		$gender = $user_profile['gender']=='male'?0:1;
	}else{
		$gender = 2;
	}
	
	$data = array(
			'user_id' => $user_profile['id'],
			'name'	=>	$user_profile['name'],
			'birth_date' => $birth_date,
			'gender' => $gender,
			'picture_url' => "",
			'expiry_date' => ''
	);
	return $data;
}

// get the user friend ids array from facebook graph api /friends query
function get_ids_from_friends($user_friends){
	$user_friends = $user_friends['data'];
	$data = array();
	foreach($user_friends as $friend){
		$data[] = $friend['id'];
	}
	return $data;
}

function get_data_from_checkin($user_checkin){
	$data = array();
	$data['checkin_id'] = $user_checkin['id'];
	$data['create_date'] = $user_checkin['created_time'];
	$data['user_id'] = $user_checkin['from']['id'];
	$location = $user_checkin['place'];
	$data['location_id'] = $location['id'];
	$data['name'] = $location['name'];
	$data['latitude'] = $location['location']['latitude'];
	$data['longitude'] = $location['location']['longitude'];
	$data['picture_url'] = '';
	$data['description'] = '';
	$data['street'] = isset($location['location']['street'])?$location['location']['street']:'';
	$data['city'] = isset($location['location']['city'])?$location['location']['city']:'';
	$data['state'] = isset($location['location']['state'])?$location['location']['state']:'';
	$data['country'] = isset($location['location']['country'])?$location['location']['country']:'';
	$data['zip'] = isset($location['location']['zip'])?$location['location']['zip']:'';
	
	return $data;
}
?>