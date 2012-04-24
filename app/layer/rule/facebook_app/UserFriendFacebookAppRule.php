<?php
require_once getRuleFilePath("Rule");

class UserFriendFacebookAppRule extends Rule {
	
	public function insertUserFriend($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		
		$sql = "insert into user_friend (user_id_from, user_id_to, created_date) values ("  . $data["user_id_from"] .
		 ", " . $data["user_id_to"] . ", '$created_date')";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->setData($sql);
	}
	
	
}
?>
