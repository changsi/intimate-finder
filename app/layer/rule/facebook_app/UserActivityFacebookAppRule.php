<?php
require_once getRuleFilePath("Rule");

class UserLocationFacebookAppRule extends Rule {
	
	public function insertUserLocation($data) {
		$sql = "insert ignore into user_location (checkin_id, user_id, location_id, create_date) values(".$data["checkin_id"].", "
		.$data["user_id"].", ".$data["location_id"].", '".$data["create_date"]."')";

		return $this->setData($sql);
	}
	
	public function getLocationAndScoreByUserID($data){
		$sql = "select location_id, sum(1/log(if((DATEDIFF(Now(), create_date)+1)=1, 2,(DATEDIFF(Now(), create_date)+1)))) as score 
		,count(*) as frequency 
		from user_location where user_id = ".$data["user_id"] . " group by location_id";
		
		return $this->getData($sql);
	}
	
	public function getUserIDsByLocationID($data){
		$sql = "select user_id , count(*) as frequency from user_location where location_id = ".$data['location_id']." group by user_id";
		
		return $this->getData($sql);
	}
	
	public function getUserIDsScoreAndFrequencyByLocationIDExceptforMyself($data, $user_id){
		$sql = "select user_id , sum(1/log(if((DATEDIFF(Now(), create_date)+1)=1, 2,(DATEDIFF(Now(), create_date)+1)))) as score 
		, count(*) as frequency 
		from user_location where location_id = ".$data['location_id']." and user_id !=".$user_id." group by user_id";
	
		return $this->getData($sql);
	}
	
	
	
	 
}
?>
