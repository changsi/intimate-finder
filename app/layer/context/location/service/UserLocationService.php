<?php

require_once getRuleFilePath("facebook_app.UserLocationFacebookAppRule");

class UserLocationService {
	private $userLocationFacebookAppRule;


	public function __construct() {
		$this->userLocationFacebookAppRule = new UserLocationFacebookAppRule();
	}


	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userLocationFacebookAppRule->setDBDriver($DBDriver);
	}

	public function insertUserLocation($data){
		return $this->userLocationFacebookAppRule->insertUserLocation($data);
	}
	
	public function getLocationAndScoreByUserID($data){
		return $this->userLocationFacebookAppRule->getLocationAndScoreByUserID($data);
	}
	
	public function getUserIDsByLocationID($data){
		return $this->userLocationFacebookAppRule->getUserIDsByLocationID($data);
	}
	//{"location_id"=>123456}
	public function getUserIDsScoreAndFrequencyByLocationIDExceptforMyself($data, $user_id){
		return $this->userLocationFacebookAppRule->getUserIDsScoreAndFrequencyByLocationIDExceptforMyself($data, $user_id);
	}
}
?>