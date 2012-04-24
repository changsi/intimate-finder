<?php
require_once getRuleFilePath("Rule");

class UserLocationFacebookAppRule extends Rule {
	
	public function insertUserLocation($data) {
		$sql = "insert ignore into user_location (checkin_id, user_id, location_id, create_date) values(".$data["checkin_id"].", "
		.$data["user_id"].", ".$data["location_id"].", '".$data["create_date"]."')";

		return $this->setData($sql);
	}
	
	
	
	 
}
?>
