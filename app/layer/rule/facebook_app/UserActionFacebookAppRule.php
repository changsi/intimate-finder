<?php
require_once getRuleFilePath("Rule");

class UserActionFacebookAppRule extends Rule {

	public function insertUserAction($data) {
		
		$sql = "insert into user_action (action_type_id, network_user_id, network_id, object_type_id, object_id) values (" . $data["action_type_id"] . ", ".$data["network_user_id"].", ". $data["network_id"]." , " . $data["object_type_id"] . ", " . $data["object_id"] . ")";
		return $this->setData($sql);
	}
	
	public function getUserLikeCountsForObject($data ) {
		$sql = "SELECT count(*) as count FROM user_action WHERE action_type_id = ".$data["action_type_id"]." and object_id = ".$data["object_id"]." and network_id = ".$data["network_id"].";";
		
		return $this->getData($sql);
	}
	
	public function getUserLikeForObject($data) {
		$sql = "SELECT count(*) as count from user_action WHERE action_type_id = ".$data["action_type_id"]." and object_id = ".$data["object_id"]." and object_type_id = ".$data["object_type_id"]." and network_user_id = ".$data["network_user_id"]." and network_id = ".$data["network_id"]. ";";
		return $this->getData($sql);
	}
	
	public function deleteUserAction($data) {
		$sql = "delete from user_action where action_type_id = " . $data["action_type_id"] . " and network_user_id = ".$data["network_user_id"]." and object_type_id = " . $data["object_type_id"] . " and object_id = " . $data["object_id"] ." and network_id = ".$data["network_id"]. ";";
		return $this->setData($sql);
	}
}

?>
