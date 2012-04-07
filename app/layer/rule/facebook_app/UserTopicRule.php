<?php
require_once getRuleFilePath("Rule");

class UserTopicRule extends Rule {
	
	public function insertTopic($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$sql = "insert ignore into topic (topic_id, topic, created_date) values(".$data["topic_id"].", '".$this->addSlashes($data['topic'])."','".$created_date."')";
	
		return $this->setData($sql);
	}
	
	public function getAllTopics(){
		$sql = "select topic_id from topic";
		
		return $this->getData($sql);
	}
	
}

?>