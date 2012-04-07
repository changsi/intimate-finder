<?php
require_once getRuleFilePath("Rule");

class DpToDpCPRule extends Rule {
	
	public function getTribeDPs($data) {
		$sql = "SELECT dp_id_from as dp_id FROM dp_dp where affinity > ".$data['min_affinity']." and dp_id_to=".$data['tribe_id']." union select dp_id_to as dp_id from dp_dp where affinity > ".$data['min_affinity']." and dp_id_from=".$data['tribe_id'];
		
		return $this->getData($sql);
	}
	
	public function getTribesByDP($data) {
		$sql = "SELECT DISTINCT dp_id_from as dp_id FROM dp_dp where dp_id_to in(".$data['dp_ids'].") and affinity >=".$data['MIN_AFFINITY_TRIBE_DP'];

		return $this->getData($sql);
	}
	
	public function getTribeUsers($data) {
		$sql = "SELECT dpu.user_id as user_id FROM dp_dp dp inner join dp_user dpu on dp.dp_id_to = dpu.dp_id where dp.affinity >= ".$data['min_affinity']." and dp_id_from=".$data['tribe_id'] ;
		
		return $this->getData($sql);
	}
}
?>
