<?php
require_once getRuleFilePath("Rule");

class NetworkFriendFacebookAppRule extends Rule {
	
	public function insertUserNetworkFriend($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		
		$sql = "insert into network_friend (network_id, network_user_id_from, network_user_id_to, created_date) values (" . $data["network_id"] . ", " . $data["network_user_id_from"] . ", " . $data["network_user_id_to"] . ", '$created_date') ON DUPLICATE KEY UPDATE control_flag = 0";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->setData($sql);
	}
	
	public function updateUserNetworkFriend($data) {
		$sql = "update network_friend set control_flag = " . $data['control_flag'] . " where network_user_id_from = " . $data['network_user_id_from'] . " and network_id = " . $data['network_id'] . ";";
		
		return $this->setData($sql);
	}
	
	public function getUserNetworkFriendsAndTheirInfo($data) {
		$sql = "select * from (select distinct (c1.network_user_id_to) as friend_id from network_friend  c1, network_friend  c2 where c1.network_id=". $data["network_id"]." and c2.network_id=". $data["network_id"]." and (c1.network_user_id_from = ". $data["network_user_id"]." or c1.network_user_id_to = ". $data["network_user_id"].") union select distinct (c1.network_user_id_from) as friend_id from network_friend  c1, network_friend  c2 where c1.network_id=". $data["network_id"]." and c2.network_id=". $data["network_id"]." and (c1.network_user_id_from = ". $data["network_user_id"]." or c1.network_user_id_to = ". $data["network_user_id"]." )) X inner join user_network un on X.friend_id = un.network_user_id where X.friend_id != ". $data["network_user_id"] ." and un.network_id=". $data["network_id"];

		return $this->getData($sql);
	}
	
	
	//$data = array("start" => "0", "limit" => "100")
	public function getAllNetworkFriendsMapping($data) {
		$sql = "SELECT * FROM network_friend WHERE control_flag = 0 ORDER BY created_date ASC LIMIT " . $data["start"] . ", " . $data["limit"] ;
		
		return $this->getData($sql);
	}
	
	//get unprocessed friends count
	public function getAllNetworkFriendsMappingCount() {
		$sql = "SELECT count(*) as count FROM network_friend WHERE control_flag = 0";
		
		return $this->getData($sql);
	}
	
	//get processed user id
	//$data = array("start" => "0", "limit" => "100")
	public function getProcessedUserIds($data) {
		$sql = "SELECT network_id, network_user_id_from FROM network_friend WHERE control_flag = 2  group by network_id,network_user_id_from LIMIT " . $data["start"] . ", " . $data["limit"] ;
		return $this->getData($sql);
	}
	
	//get distinct processed user id count
	public function getProcessedUserIdsCount() {
		$sql = "SELECT count(DISTINCT network_user_id_from) as count FROM network_friend WHERE control_flag = 2;";
		return $this->getData($sql);
	}
	
	//update control flag by network_user_id_from
	//$data = array("network_id" => 1, "control_flag" => 1, "network_user_id_from" => 234);
	public function updateUserNetworkFriendControlFlagByMainUser($data) {
		$sql = "UPDATE network_friend SET control_flag = " . $data["control_flag"] . " WHERE network_id = " . $data["network_id"] . " AND network_user_id_from = " . $data["network_user_id_from"];
	
		return $this->setData($sql);
	}
	
	public function deleteNetworkFriendControlFlagByMainUserFromFile($data) {
		
		$sql = "DELETE from network_friend where control_flag = " . $data["control_flag"] . " and network_user_id_from = " . $data["network_user_id_from"] . " and network_id = " . $data["network_id"] ;
		return $this->setData($sql);
		
	}
	//update Network Friends' control flags
	//$data = array("network_id" => 1, "control_flag" => 1, "network_user_id_from" => 234, "network_user_id_to" => 34343);
	public function updateUserNetworkFriendControlFlag($data) {
		$sql = "UPDATE network_friend SET control_flag = " . $data["control_flag"] . " WHERE network_id = " . $data["network_id"] . " AND network_user_id_from = " . $data["network_user_id_from"] . " AND network_user_id_to = " . $data["network_user_id_to"];
		return $this->setData($sql);
	}

	public function getUserFriends($data){
		$sql = "select network_user_id_to from network_friend where network_id = ".$data["network_id"]." and network_user_id_from =". $data["user_id_from"];
		
		return $this->getData($sql);
	}
}
?>
