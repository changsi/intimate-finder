<?php
require_once getRuleFilePath("Rule");
require_once getLibFilePath("util.StringHelper");


class UserFriendsObjectCounterFacebookAppRule extends Rule {
	public function insertUserFriendsObjectCounter($data) {
		$created_date = $this->getCurrentTimestamp();
		
		$sql = "insert into user_friends_object_counter (network_user_id, object_id, category, count,likes, control_flag, create_date) values(".$data["network_user_id"].", ".$data['object_id'].", '".$data["category"]."', ".$data['count'].", ".$data['likes'].", ".$data['control_flag'].",'".$created_date."') on duplicate key update count=values(count), create_date=values(create_date)";

		return $this->setData($sql);
	}
	
	public function getTopObject($data){
	
		$sql = "select object_id from user_friends_object_counter where network_user_id =".$data['network_user_id']." and category='".$data['category']."' order by count, likes desc limit ".$data['limit'];
		return $this->getData($sql);
	}
}


?>
