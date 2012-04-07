<?php
require_once getRuleFilePath("Rule");

class UserToUserFacebookAppRule extends Rule {

	public function insertUserAction($data) {
		$sql = "insert into user_action (action_type_id, network_user_id, network_id, object_type_id, object_id) values (" . $data["action_type_id"] . ", ".$data["network_user_id"].", ". $data["network_id"]." , " . $data["object_type_id"] . ", " . $data["object_id"] . ")";
		return $this->setData($sql);
	}
	
	
	public function updateAffinityForAffinedUsers($data) {
		$affinity = $data["affinity"];
		$hash_user_id_to = $data["hash_user_id_to"];
		$hash_user_id_from = $data["hash_user_id_from"];
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$sql = "insert into user_user (hash_user_id_from, hash_user_id_to, affinity, created_date ) values (". $hash_user_id_from. ",".$hash_user_id_to. ",".$affinity.",'$created_date') ON DUPLICATE KEY UPDATE affinity = ". $affinity ;
		return $this->setData($sql);
	}
	
	public function getTopAffinedUsers($data) {
		$hash_user_id_from = $data["hash_user_id_from"];
		$limit = $data["limit"];
		
		$sql = "SELECT uu.hash_user_id_to, uu.affinity, un.network_user_id FROM user_user uu, user_network un WHERE hash_user_id_from = ".$hash_user_id_from. " and un.hash_user_id = uu.hash_user_id_to order by affinity desc limit ".$limit ;
		return $this->getData($sql);
	}
	
	public function getUsersWithThresholdAffinty($data) {
		$hash_user_id_from = $data["hash_user_id_from"];
		$threshold = $data["threshold"];
		$sql = "SELECT un.network_user_id,uu.hash_user_id_to, uu.affinity FROM user_user uu, user_network un WHERE un.hash_user_id =uu.hash_user_id_to and uu.hash_user_id_from = ".$hash_user_id_from. " and uu.affinity > ".$threshold ;
		return $this->getData($sql);
	}
}

?>
