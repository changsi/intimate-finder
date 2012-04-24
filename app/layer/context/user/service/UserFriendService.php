<?php
require_once getRuleFilePath("facebook_app.UserFriendFacebookAppRule");
require_once getLibFilePath("io.FileHandler");

class UserFriendService {
	private $userFriendFacebookAppRule;
	
		
	public function __construct() {
		$this->userFriendFacebookAppRule = new UserFriendFacebookAppRule();
	}
	
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userFriendFacebookAppRule->setDBDriver($DBDriver);
	}
	
	public function insertUserFriend($data){
		return $this->userFriendFacebookAppRule->insertUserFriend($data);
	}
}
?>
