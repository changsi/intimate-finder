<?php

require_once getRuleFilePath("Rule");

class UserObjectFacebookAppRule extends Rule {

	public function insertUserObject($data) {
		$sql = "insert ignore into user_object (user_id, object_id, object_name, category, create_date)
		values(".$data["user_id"].", ".$data["object_id"].", '".mysql_real_escape_string($data["object_name"])
		."','".mysql_real_escape_string($data['category'])."', '".$data["created_time"]."')";

		return $this->setData($sql);
	}
	
	public function getObjectsByUserID($data){
		$sql = "select object_id, object_name, category, create_date from user_object where user_id = ".$data['user_id'];
		
		return $this->getData($sql);
	}

}

?>