<?php

require_once getRuleFilePath("Rule");

class DownloadingProgressFacebookAppRule extends Rule {

	public function insertProgress($data) {

		$sql = "insert into downloading_progress (user_id, progress, control_flag) values ("  .
				$data["user_id"] .", ".$data["progress"].", ".$data['control_flag'].
				") ON DUPLICATE KEY UPDATE progress=VALUES(progress);";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->setData($sql);
	}
	
	public function deleteProgress($data){
		$sql = "delete from downloading_progress where user_id = ".$data['user_id'].";";
		return $this->setData($sql);
	}
	
	public function getProgress($data){
		$sql = "select * from downloading_progress where user_id=".$data['user_id'].";";
		return $this->getData($sql);
	}
	
	


}

?>