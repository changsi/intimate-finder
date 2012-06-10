<?php

require_once getRuleFilePath("facebook_app.UserObjectFacebookAppRule");




class UserObjectService {
	private $userObjectFacebookAppRule;


	public function __construct() {
		$this->userObjectFacebookAppRule = new UserObjectFacebookAppRule();
	}

	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userObjectFacebookAppRule->setDBDriver($DBDriver);

	}


	public function insertUserObject($data){
		return $this->userObjectFacebookAppRule->insertUserObject($data);
	}
	
	public function getObjectsByUserID($data){
		return $this->userObjectFacebookAppRule->getObjectsByUserID($data);
	}
}

?>