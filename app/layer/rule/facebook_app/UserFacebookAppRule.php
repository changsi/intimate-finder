<?php
require_once getRuleFilePath("Rule");

class UserFacebookAppRule extends Rule {
	
	public function insertUserNetwork($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
	
		$sql = "insert into user (user_id, name,birth_date, gender, created_date, access_token, picture_url, expiry_date, modified_date) values 
		(" . $data["user_id"] . ", '" . mysql_real_escape_string($data["name"] ). "', '" . $data["birth_date"] . "', " . 
		$data["gender"] . ", '$created_date', '". $data["access_token"]."' , '".  $data["picture_url"]."', '".
		$data["expiry_date"]."', '".$created_date."') ON DUPLICATE KEY UPDATE 
		name=VALUES(name), access_token=values(access_token), modified_date=NOW()" ;
		//echo "$sql<br>";
	
		return $this->setData($sql);
	}
	
	public function updateExpiryDateForLiveSystem($data){
		$sql = "update user set expiry_date = '".$data['expiry_date']."' where network_user_id = ".$data['network_user_id']." and network_id = ".$data['network_id'];
	
		return $this->setData($sql);
	}
	
	public function getExpiredUserFromLiveSystem ($data){
		$current_date = $this->getCurrentTimestamp();
		$sql = "select user_id, access_token from user where access_token <> '' and expiry_date <'".$current_date."' limit ".$data['start'].", ".$data['limit'];
		return $this->getData($sql);
	}
	
	public function getUserInfoFromLiveSystem($data) {
		$sql = "select * from user where user_id = ".$data['user_id'];
		//echo "$sql<br>";
		return $this->getData($sql);
	}
	
	public function getUserTokenFromLiveSystem($data){
		$id = $data['user_id'];
		$sql = 'select access_token, user_id, name from user where user_id = '.$id;
	
		return $this->getData($sql);
	}
	
	
	
	
	public function updateUserAccessToken($data) {
		$sql = "update user set access_token = '".$data["access_token"] . "' where user_id = ".$data["user_id"];
	
		return $this->setData($sql);
	}
	
}
?>
