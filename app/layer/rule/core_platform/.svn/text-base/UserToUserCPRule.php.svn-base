<?php
require_once getRuleFilePath("Rule");

class UserToUserCPRUle extends Rule {
	
	
	public function getAffinedUsersWtihAffinity($data) {
		$hash_user_id_from = $data["hash_user_id_from"];
		$MIN_USER_TO_USER_AFFINITY = $data["MIN_USER_TO_USER_AFFINITY"];
		$sql = "select user_id_to, affinity from user_user where user_id_from = ".$hash_user_id_from ." and affinity > " . $MIN_USER_TO_USER_AFFINITY;
		return $this->getData($sql);
	}
	
}
?>
