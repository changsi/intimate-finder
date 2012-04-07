<?php
require_once getRuleFilePath("Rule");

class UserNetworkDataFacebookAppRule extends Rule {
	
	
	public function getCountOfAllNetworkUsersProfilesWithControlFlag($data) {
		$sql = "select count(*) as count from user_network_data where control_flag = 0 and network_id = ".$data["network_id"];

		return $this->getData($sql);
	}
	
	public function getAllNetworkUsersProfilesWithControlFlag($data) {
		$sql = "select * from user_network_data where control_flag = 0 and network_id = ".$data["network_id"];

		return $this->getData($sql);
	}
	
	//update control flag
	//$data = array("network_id" => 1, "control_flag" => 1, "network_post_id" => "1", "url_id" => "11112")
	public function updateUserNetworkDataControlFlag($data) {	
		$sql = "UPDATE user_network_data SET control_flag = " . $data["control_flag"] . " WHERE network_id = " . $data["network_id"] . " AND network_user_id = " . $data["network_user_id"];
	
		return $this->setData($sql);
	}
	
	public function insertUserNetworkProfile($data) {
		$age = isset($data['age']) ? $data['age'] : '0';
		$gender = isset($data['gender']) ? $data['gender'] : '0';
		$education = isset($data['education']) ? $this->addSlashes($data['education']) : '';
		$current_location = isset($data['current_location']) ? $this->addSlashes($data['current_location']) : '';
		$from_location = isset($data['from_location']) ? $this->addSlashes($data['from_location']) : '';
		$email = isset($data['email']) ? $this->addSlashes($data['email']) : '';
		$relationship_id = isset($data['relationship_id']) ? $data['relationship_id'] : '0';
		
		$sql = "insert into user_network_data (network_id, network_user_id, age, gender, education, current_location, from_location, relationship_id, email) values (".$data['network_id'].", ".$data['network_user_id'].", $age, $gender, '$education', '$current_location', '$from_location', $relationship_id, '$email') on duplicate key update age=values(age), age=values(age), gender=values(gender), education=values(education), current_location=values(current_location), from_location=values(from_location), relationship_id=values(relationship_id), email=values(email)";

		return $this->setData($sql);
	}
	
	public function getUserNetworkProfile($data) {
		$sql = "select * from user_network_data und inner join relationship r on und.relationship_id = r.id where network_user_id = ".$data["network_user_id"]." and network_id = ".$data["network_id"];

		return $this->getData($sql);
	}
	
	public function getEmail($data) {
		$sql = "SELECT email from user_network_data und , user_network un where und.network_user_id = un.network_user_id and un.hash_user_id = ".$data["user_id"].";" ; 
		
		return $this->getData($sql);
	}
}
?>
