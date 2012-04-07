<?php
require_once getRuleFilePath("Rule");

class UserTribeFacebookAppRule extends Rule {
	
	public function insertUserTribe($data){
		$network_id = $data["network_id"] ;
		$network_user_id = $data["network_user_id"] ; 
		$tribe_id = $data["tribe_id"];
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$sql = "insert into item_of_day (network_id, network_user_id, tribe_id, created_date) values (" . $network_id . "," . $network_user_id . "," . $tribe_id . ",".$created_date." )";
		return $this->setData($sql); 
	}
	
	public function getTribeByNetworkUserId($data) {
		$sql =  "select * from user_tribe where network_id = ".$data['network_id']." and network_user_id = ".$data['network_user_id'];
		return $this->getData($sql);
	}
	
	public function saveUserTribe($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$sql =  "insert into user_tribe values(".$data['network_id'].", ".$data['network_user_id'].", ".$data['tribe_id'].", '$created_date', CURRENT_TIMESTAMP)";
		echo $sql;
		return $this->setData($sql);
	}
	
	public function getUserFriendsInThatTribe($data) {
		$extra = '';
		if (isset($data['limit'])) {
			$extra = 'limit 0,'.$data['limit'];
		}
		$sql = "select * from user_tribe ut inner join network_friend nf on (ut.network_user_id = nf.network_user_id_to and ut.network_id = nf.network_id) inner join  user_network un on (nf.network_id = un.network_id and nf.network_user_id_to = un.network_user_id) where nf.network_id =".$data['network_id']." and nf.network_user_id_from =".$data['network_user_id']." and ut.tribe_id=".$data['tribe_id']." ".$extra ;

		return $this->getData($sql);
	}
	
	public function getTribeUsersInLive($data) {
		$sql = "select * from user_tribe ut inner join user_network un on (ut.network_id=un.network_id and ut.network_user_id=un.network_user_id) where tribe_id=".$data['tribe_id'];	
		return $this->getData($sql);
	}
	
	public function getTribeByUserId($data) {
		$sql = "select * from user_tribe ut inner join tribe t on ut.tribe_id = t.id where network_id=".$data['network_id']." and network_user_id=".$data['network_user_id'];
		return $this->getData($sql);
	}
	
	public function getTribesForUserFriends($data) {
		$sql = "select *, tr.name as tribe_name, un.name as friend_name from network_friend nf inner join user_tribe ut on (nf.network_id = ut.network_id and nf.network_user_id_to = ut.network_user_id) inner join user_network un on (un.network_id = nf.network_id and un.network_user_id = nf.network_user_id_to) inner join tribe tr on (tr.id = ut.tribe_id) where nf.network_user_id_from =".$data['network_user_id']." and nf.network_id=".$data['network_id']." order by ut.tribe_id asc";
		return $this->getData($sql);
	}
	
	public function getUserFriendsThatDontHaveATribeInLive($data) {
		$extra = '';
		if (isset($data['limit'])) {
			$extra = 'limit 0,'.$data['limit'];
		}
		
		$sql = "select network_user_id_to as network_user_id from network_friend nf left join user_tribe ut on (nf.network_id = ut.network_id and nf.network_user_id_to = ut.network_user_id) where nf.network_id = ".$data["network_id"]." and nf.network_user_id_from = ".$data["network_user_id"]." and ut.tribe_id is NULL ".$extra ;
		return $this->getData($sql);
	}
	
}
?>
