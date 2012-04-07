<?php
require_once getRuleFilePath("Rule");

class DpCPRule extends Rule {
	
	public function insertManualDp($data) {
		$sql = "insert into dp (name, created_date, dp_type_id) values ('".$data['name']."', CURRENT_TIMESTAMP, 2)";
		echo "$sql \n";
		return $this->setData($sql);
	}
	
	public function updateManualDp($data) {
		$sql = "update dp set name='".$data['name']."' where dp_type_id = 2 and id =".$data['dp_id'];
		return $this->setData($sql);
	}
	
	
	public function getManualDpByName($data) {
		$sql = "SELECT * FROM dp where dp_type_id = 2 and name = '".$data["name"]."'";
		echo "$sql \n";
		return $this->getData($sql);
	}
	
	
}
?>
