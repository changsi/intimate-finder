<?php
require_once getRuleFilePath("Rule");

class DpToUserCPRule extends Rule {
	
	public function getDPsByUser($data) {
		$sql = "SELECT * FROM dp_user where user_id = ".$data["user_id"];
		
		return $this->getData($sql);
	}
	
	public function getDPsByUserFriends($data) {
		$sql = "select distinct dp_id  from (SELECT dpu.dp_id, dpu.user_id, f.user_id_from, f.user_id_to FROM dp_user dpu inner join friend f on f.user_id_from = dpu.user_id where f.user_id_to =".$data["user_id"]." union SELECT dpu.dp_id, dpu.user_id, f.user_id_from, f.user_id_to FROM dp_user dpu inner join friend f on f.user_id_to = dpu.user_id where f.user_id_from =".$data["user_id"].") X  group by X.dp_id,X.user_id";

		return $this->getData($sql);
	}
	
	public function getDPUsers($data) {
		$sql = "SELECT * FROM dp_user where dp_id = ".$data["dp_id"];
	
		return $this->getData($sql);
	}
	
	public function getManualDpsAndAffinityByUserId($data) {
		$sql = "SELECT * FROM dp_user du inner join dp on (du.dp_id=dp.id) where du.user_id = ".$data["user_id"]." and dp.dp_type_id=2 order by du.affinity desc";
	
		return $this->getData($sql);
	}
	
	public function getDPsForLiveSystem($data) {
		$sql = "SELECT * FROM dp_user dpu, dp where dpu.dp_id = dp.id and dpu.user_id = ".$data["user_id"]." and dp.dp_type_id = ".$data["dp_type_id"].";";
		return $this->getData($sql);
	}
	
	public function getFriendsHavingTheSameTribeInCP($data) {
		$ids = $this->getCSVStringFromArray($data["friends"]);
		$sql = " select user_id as hash_user_id, max(affinity) as affinity from dp_user where user_id in ($ids) and dp_id = ".$data["tribe_id"]." group by user_id having affinity > ".$data["affinity_threshold"];
		return $this->getData($sql);
	}
}
?>
