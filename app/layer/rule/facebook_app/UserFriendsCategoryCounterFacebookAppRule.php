<?php
require_once getRuleFilePath("Rule");
require_once getLibFilePath("util.StringHelper");


class UserFriendsCategoryCounterFacebookAppRule extends Rule {
	public function insertUserFriendsCategoryCounter($data) {
		$created_date = $this->getCurrentTimestamp();
		
		$sql = "insert into user_friends_category_counter (network_user_id, category, count, control_flag, create_date) values(".$data["network_user_id"].", '".$data["category"]."', ".$data['count'].", ".$data['control_flag'].",'".$created_date."') on duplicate key update count=values(count), create_date=values(create_date)";

		return $this->setData($sql);
	}
	
	public function getTopCategory($data){
	
		$sql = "select category from user_friends_category_counter where network_user_id =".$data['network_user_id']." order by count desc limit ".$data['limit'];
		return $this->getData($sql);
	}
}


?>
