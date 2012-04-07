<?php
require_once getRuleFilePath("Rule");

class TribeCategoryFacebookAppRule extends Rule {
	
	public function insertTribeCategories($data) {
		$return = false;
		
		if (isset($data['categories'])) {
			$sql = "insert into tribe_category (tribe_id, category_id, affinity, created_date) values" ;
			$c = 0;
			foreach ($data['categories'] as $key => $affinity) {
				$sql .= $c ? "," : "";
				$sql .= "( ".$data["dp_id"].", $key, $affinity, CURRENT_TIMESTAMP) ";
				$c++;
			}
			$sql .= "on duplicate key update affinity=VALUES(affinity)";
			$this->setData($sql);
			$return = true;
		}
		if (isset($data['profile_categories'])) {
			$sql = "insert into tribe_category (tribe_id, category_id, affinity, created_date) values" ;
			$c = 0;
			foreach ($data['profile_categories'] as $key => $affinity) {
				$sql .= $c ? "," : "";
				$sql .= "( ".$data["dp_id"].", $key, $affinity, CURRENT_TIMESTAMP) ";
				$c++;
			}
			$sql .= "on duplicate key update affinity=VALUES(affinity)";
			$this->setData($sql);
			$return = true;
		}
		return $return;
	}
	
	public function deleteTribeCategories ($data) {
		$sql = "delete from tribe_category where tribe_id =".$data['dp_id'];

		return $this->setData($sql);
	}
	
	public function getTribeCategories ($data) {
		$sql = "SELECT * FROM tribe_category where tribe_id = ".$data["tribe_id"]." order by affinity desc";
		
		return $this->getData($sql);
	}
}
?>

