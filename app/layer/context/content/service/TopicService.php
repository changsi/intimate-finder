<?php

require_once getRuleFilePath("facebook_app.UserTopicRule");

class TopicService {
	private $userTopicRule;
	
	public function __construct() {
		$this->userTopicRule = new UserTopicRule();
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userTopicRule->setDBDriver($DBDriver);
		
	}
	
	public function setUserTopic($data){
		return $this->userTopicRule->insertTopic($data);
	}
	
	public function getAllTopics(){
		return $this->userTopicRule->getAllTopics();
		
	}
}

?>