<?php
/*
Contains functions for:
- entity/content/like.php
- entity/content/trend_user_content.php
- script/start_preparing_trended_user_content.php.php
*/
require_once getRuleFilePath("facebook_app.UserObjectFacebookAppRule");
require_once getRuleFilePath("facebook_app.ObjectFacebookAppRule");
require_once getRuleFilePath("facebook_app.ObjectCountFacebookAppRule");
require_once getRuleFilePath("facebook_app.ObjectTypeFacebookAppRule");
require_once getRuleFilePath("facebook_app.RecommendedUserObjectFacebookAppRule");
require_once getRuleFilePath("facebook_app.UserActionFacebookAppRule");
require_once getRuleFilePath("facebook_app.NetworkPostUrlFacebookAppRule");
require_once getRuleFilePath("facebook_app.BookMarkFacebookAppRule");
require_once getRuleFilePath("facebook_app.UserFriendsCategoryCounterFacebookAppRule");
require_once getRuleFilePath("facebook_app.UserFriendsObjectCounterFacebookAppRule");

require_once getRuleFilePath("core_platform.FriendCPRule");
require_once getContextFilePath("common.service.IdService");
require_once getLibFilePath("util.StringHelper");

class ContentService {
	private $userObjectFacebookAppRule;
	private $objectFacebookAppRule;
	private $objectCountFacebookAppRule;
	private $friendCPRule;
	private $objectTypeFacebookAppRule;
	private $recommendedUserObjectFacebookAppRule;
	private $userActionFacebookAppRule;
	private $networkPostUrlFacebookAppRule;
	private $bookMarkFaceBookAppRule;
	private $userFriendsCategoryCounterFacebookAppRule;
	private $userFriendsObjectCounterFacebookAppRule;
	
	public function __construct() {
		$this->userObjectFacebookAppRule = new UserObjectFacebookAppRule();
		$this->objectFacebookAppRule = new ObjectFacebookAppRule();
		$this->objectCountFacebookAppRule = new ObjectCountFacebookAppRule();
		$this->friendCPRule = new FriendCPRule();
		$this->objectTypeFacebookAppRule = new ObjectTypeFacebookAppRule();
		$this->recommendedUserObjectFacebookAppRule = new RecommendedUserObjectFacebookAppRule();	
		$this->userActionFacebookAppRule = new UserActionFacebookAppRule();	
		$this->networkPostUrlFacebookAppRule = new NetworkPostUrlFacebookAppRule();
		$this->bookMarkFaceBookAppRule = new BookMarkFacebookAppRule();
		$this->userFriendsCategoryCounterFacebookAppRule = new UserFriendsCategoryCounterFacebookAppRule();
		$this->userFriendsObjectCounterFacebookAppRule = new UserFriendsObjectCounterFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->friendCPRule->setDBDriver($DBDriver);
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userObjectFacebookAppRule->setDBDriver($DBDriver);
		$this->objectFacebookAppRule->setDBDriver($DBDriver);
		$this->objectCountFacebookAppRule->setDBDriver($DBDriver);
		$this->objectTypeFacebookAppRule->setDBDriver($DBDriver);
		$this->recommendedUserObjectFacebookAppRule->setDBDriver($DBDriver);
		$this->userActionFacebookAppRule->setDBDriver($DBDriver);
		$this->networkPostUrlFacebookAppRule->setDBDriver($DBDriver);
		$this->bookMarkFaceBookAppRule->setDBDriver($DBDriver);
		$this->userFriendsCategoryCounterFacebookAppRule->setDBDriver($DBDriver);
		$this->userFriendsObjectCounterFacebookAppRule->setDBDriver($DBDriver);
	}
	
	/* START GET FUNCTIONS */
	
	//$data = array("user_id" => 123123);
	//to be used by entity/content/trend_user_content.php
	public function getRecommendedUserContent($data) {
		return $this->recommendedUserObjectFacebookAppRule->getRecommendedUserContent($data);
	}
	
	
	
	//$data = array("friends_ids" => array(0=> 3215, 1 =>63156 , 2 => 345));
	//to be used by script/start_preparing_trended_user_content.php.php
	public function getContentTypes() {
		return $this->objectTypeFacebookAppRule->getContentTypes();
	}
	
	
	
	public function getObjectByID($data){
		$result = $this->objectFacebookAppRule->getObjectByID($data);
		if(isset($result[0])){
			return $result[0];
		}
		return $result;
	}
	
	//$data = array("object_ids" => 123123,21456,14255 , "object_type_id" => "1")
	public function getObjectByIDs($data){
		$result = $this->objectFacebookAppRule->getObjectByIDs($data);
		return $result;
	}
	
	//$data = array("object_ids" => 123123,21456,14255 )
	public function getUrlByIDs($data) {
		$result = $this->networkPostUrlFacebookAppRule->getUrlByIDs($data);
		return $result;
	}
	
	//$data = array("network_user_id" => 123123, "object_type_id" => 1, "object_id" => 12123);
	//to be used by entity/content/like.php
	public function likeContent($data) {
		//TODO
		$this->userActionFacebookAppRule->insertUserAction($data);
		
		$result = $this->userActionFacebookAppRule->getUserLikeCountsForObject($data);
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		return $count;
		//Additionally publish this action in the Facebook wall. => Call the $SNFacebookService->publishPostInUserWall($data);
	}
	//$data = array("network_user_id" => 123123, "object_type_id" => 1, "object_id" => 12123);
	public function unlikeContent($data){
		$this->userActionFacebookAppRule->deleteUserAction($data);
		$result = $this->userActionFacebookAppRule->getUserLikeCountsForObject($data);
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		return $count;
	}
	//$data = array("object_type_id" => 1, "object_id" => 12123);
	public function getObjectLikeCounts($data){
		$result = $this->userActionFacebookAppRule->getUserLikeCountsForObject($data);
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		return $count;
	}
	//$data = array("network_user_id" => 123123, "object_type_id" => 1, "object_id" => 12123);
	// return value 1 or 0 
	// 1 means you already liked it
	// 0 means you have not liked it
	public function checkLikeStatus($data){
		$result = $this->userActionFacebookAppRule->getUserLikeForObject($data);
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		else{
			return false;
		}
		if($count > 0){
			return 1;
		}
		else{
			return 0;
		}
		
	}
	
	
	//$data = ["name" => "abc" , "url" => "abc.com", author => "xyz", genre =>"", genre2=>"",object_type_id=>"9",network_object_id=>"0"];
	//insert MANUAL object in object table, creates a new object_id after hashing the normalized name
	public function insertManualObject($data){
		return $this->objectFacebookAppRule->insertManualObject($data);
	}
	
	public function getTopCategory($data){
		return $this->userFriendsCategoryCounterFacebookAppRule->getTopCategory($data);
	}
	
	public function insertUserFriendsCategory($data){
		return $this->userFriendsCategoryCounterFacebookAppRule->insertUserFriendsCategoryCounter($data);
	}
	
	public function getTopObject($data){
		return $this->userFriendsObjectCounterFacebookAppRule->getTopObject($data);
	}
	
	
	
	public function insertUserFriendsObject($data){
		return $this->userFriendsObjectCounterFacebookAppRule->insertUserFriendsObjectCounter($data);
	}
	

	public function insertObject($data){
		return $this->objectFacebookAppRule->insertObject($data);
	}
	
	public function insertUserObject($data){
		return $this->userObjectFacebookAppRule->insertUserObject($data);
	}

	
	public function updateObjectCount() {
		return $this->objectCountFacebookAppRule->updateObjectCount();
	}
	
}
?>
