<?php 

function get_data_from_profile($user_profile){
	$birthday= date_parse($user_profile['birthday']);
	$birth_date = $birthday['year'].'-'.$birthday['month'].'-'.$birthday['day'];
	$data = array(
			'user_id' => $user_profile['id'],
			'name'	=>	$user_profile['name'],
			'birth_date' => $birth_date,
			'gender' => (isset($user_profile['gender'])?2:$user_profile['gender'])=='male'?0:1,
			'picture_url' => "",
			'expiry_date' => ''
	);
	return $data;
}

function get_ids_from_friends($user_friends){
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