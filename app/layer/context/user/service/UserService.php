<?php

require_once getRuleFilePath("facebook_app.UserFacebookAppRule");




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
	
	public function isRegisteredUser($data){
		$result = $this->userFacebookAppRule->getUserInfoFromLiveSystem($data);
		if(isset($result) && !empty($result) && isset($result[0]['access_token'])&&$result[0]['access_token']!=''){
			return true;
		}
		return false;
	}
	
	public function insertUser($data){
		if(!isset($data['access_token'])){
			$data['access_token'] = '';
		}
		return $this->userFacebookAppRule->insertUserNetwork($data);
	}
}
?>
