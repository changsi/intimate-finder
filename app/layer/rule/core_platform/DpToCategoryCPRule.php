<?php
require_once getRuleFilePath("Rule");

class DpToCategoryCPRule extends Rule {
	
	public function getDPCategories($data) {
		$sql = "SELECT * FROM dp_category dpc left join word_set ws on dpc.category_id = ws.id where dp_id = ".$data["dp_id"]." order by affinity desc";
		
		return $this->getData($sql);
	}
	
	
	/*$data = array("name" => "", "description" => "", categories => array(
		112323 => 0.3,
		5577=> 0.6,
		<category_id> => <affinity>
	));*/
	public function insertManualDpCategories($data) {
		if (isset($data['categories'])) {
			$sql = "insert into dp_category (dp_id, category_id, affinity, created_date) values" ;
			$c = 0;
			foreach ($data['categories'] as $key => $affinity) {
				$sql .= $c ? "," : "";
				$sql .= "( ".$data["dp_id"].", $key, $affinity, CURRENT_TIMESTAMP) ";
				$c++;
			}
			$sql .= "on duplicate key update affinity=VALUES(affinity)";
			echo "$sql \n";
			return $this->setData($sql);
		}
		return false;
	}
	
	
	public function deleteDpCategories($data) {
		$sql = "delete from dp_category where dp_id =".$data['dp_id'];
		echo "$sql \n";
		return $this->setData($sql);
	}
}
?>
