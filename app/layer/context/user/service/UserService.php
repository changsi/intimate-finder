<?php

require_once getRuleFilePath("facebook_app.UserFacebookAppRule");


require_once getLibFilePath("io.FileHandler");


class UserService {
	private $userFacebookAppRule;
	
	
	public function __construct() {
		$this->userFacebookAppRule = new UserFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userFacebookAppRule->setDBDriver($DBDriver);
		
	}
	
	public function getUserInfoFromLiveSystem($data){
		return $this->userFacebookAppRule->getUserInfoFromLiveSystem($data);
	}
	
	public function insertUser($data){
		return $this->userFacebookAppRule->insertUserNetwork($data);
	}
}
?>
