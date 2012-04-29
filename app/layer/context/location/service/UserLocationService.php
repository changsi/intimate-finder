<?php

require_once getRuleFilePath("facebook_app.UserLocationFacebookAppRule");
require_once getLibFilePath("io.FileHandler");

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
}
?>