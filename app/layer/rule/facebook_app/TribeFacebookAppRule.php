<?php
require_once getRuleFilePath("Rule");

class TribeFacebookAppRule extends Rule {
	
	public function insertTribe($data) { //this function updates and insert at the same time.
		if (!isset($data['description'])) {
			$data['description'] = '';
		}
		if (!isset($data['badge'])) {
			$data['badge'] = '';
		}
		if (!isset($data['slogan'])) {
			$data['slogan'] = '';
		}
		
		$sql = "insert into tribe (id, name, created_date, description, badge, slogan) values (".$data['dp_id'].", '".$this->addSlashes($data['name'])."', CURRENT_TIMESTAMP, '".$this->addSlashes($data['description'])."', '".$this->addSlashes($data['badge'])."', '".$this->addSlashes($data['slogan'])."') on duplicate key update name = values(name), description = values(description), badge = values(badge), slogan = values(slogan), modified_date=CURRENT_TIMESTAMP ";

		return $this->setData($sql);
	}

	public function getAllTribes() {
		$sql = "select * from tribe";
		return $this->getData($sql);
	}
	
	public function getTribeInfoById($data) {
		$sql = "select * from tribe where id=".$data['tribe_id'];
		return $this->getData($sql);
	}
	
	public function getTribeByName($data) {
		$sql = "select * from tribe where name='".$this->addSlashes($data['tribe_name'])."'";
		return $this->getData($sql);
	}
	
	public function getOtherTribesInfo($data) {
		$tribesIds = $this->getCSVStringFromArray($data['tribes']);
		$sql = "select * from tribe where id not in ($tribesIds)";
		return $this->getData($sql);
	}
	
	
}
?>
