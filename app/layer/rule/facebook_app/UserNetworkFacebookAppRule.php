<?php
require_once getRuleFilePath("Rule");

class UserNetworkFacebookAppRule extends Rule {
	
	
	public function insertUserNetwork($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		
		$sql = "insert into user_network (network_id, network_user_id, hash_user_id, name, screen_name, created_date, access_token,user_id, invitation_pending) values (" . $data["network_id"] . ", " . $data["network_user_id"] . ", " . $data["hash_user_id"] . ", '" . $data["name"] . "', '" . $data["screen_name"] . "', '$created_date', '".  $data["access_token"]."' , '".  $data["user_id"]."', '".$data["invitation_pending"]."') ON DUPLICATE KEY UPDATE name=VALUES(name), screen_name=VALUES(screen_name), access_token=values(access_token), modified_date=NOW()" ;
		//echo "$sql<br>";
		
		return $this->setData($sql);
	}
	
	public function updateExpiryDateForLiveSystem($data){
		$sql = "update user_network set expiry_date = '".$data['expiry_date']."' where network_user_id = ".$data['network_user_id']." and network_id = ".$data['network_id'];
		
		return $this->setData($sql);
	}
	
	public function getExpiredUserFromLiveSystem ($data){
		$current_date = $this->getCurrentTimestamp();
		$sql = "select network_user_id, network_id, access_token where network_id ".$data['network_id']." and access_token <> '' and expiry_date <'".$current_date."' limit ".$data['start'].", ".$data['limit'];
		return $this->getData($sql);
	}
	
	public function getUserTypeFromLiveSystem($data) {
		$sql = "select invitation_pending from user_network where network_user_id = ".$data;
		//echo "$sql<br>";
		return $this->getData($sql);
	}
	
	public function getUserInfoFromLiveSystem($data) {
		$sql = "select * from user_network where network_user_id = ".$data['network_user_id']." and network_id = ".$data['network_id'];
		return $this->getData($sql);
	}
	
	public function getUserTokenFromLiveSystem($data){
		$id = $data['network_user_id'];
		$sql = 'select access_token, hash_user_id, name from user_network where network_id = '.$data['network_id'].' and network_user_id = '.$id;

		return $this->getData($sql);
	}
	
	public function getUserInfoFromLiveSystemWithHashUserId($data){
		$sql = 'select * from user_network where hash_user_id = '.$data['hash_user_id'];

		return $this->getData($sql);
	}
	
	
	public function updateUserAccessToken($data) {
		$sql = "update user_network set access_token = '".$data["access_token"] . "' where network_user_id = ".$data["network_user_id"]." and network_id= ".$data["network_id"];

		return $this->setData($sql);
	}
	
	public function getCountOfUsers($data) {
		$sql = "select count(network_user_id) as count from user_network where network_id =".$data['network_id']." and access_token != ''";
		
		return $this->getData($sql);
	}
	
	public function getUsersFromFB($data) {
		$sql = "select * from user_network where network_id =".$data['network_id']." limit ".$data["start"].",".$data["limit"];

		return $this->getData($sql);
	}
	
	public function getNetworkUserFromHashUserId($data) {
		$sql = "select network_user_id from user_network where hash_user_id=".$data['hash_user_id'];
		
		return $this->getData($sql);
	}
	
	//Added By Amit
	public function getCountInvitationPendingUser($data) {
		$sql = "select count(*) as count from user_network where invitation_pending = ".$data['invitation_pending']." and network_id = ".$data['network_id'].";" ;
		return $this->getData($sql);
	}		
	
	public function getInvitationPendingUser($data) {
		$sql = "select * from user_network where invitation_pending = ".$data['invitation_pending']." and network_id = ".$data['network_id'].";" ;
		
		return $this->getData($sql);
	}	
	
	public function updatePendingInvitation($data) {
		
		$sql = "UPDATE user_network SET invitation_pending = ".$data['invitation_pending']." where hash_user_id = ".$data["hash_user_id"].";" ;
		
		return $this->setData($sql);
	}	
	
	
	

}
?>
