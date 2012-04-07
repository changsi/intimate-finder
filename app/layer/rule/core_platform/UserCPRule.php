<?php
require_once getRuleFilePath("Rule");

class UserCPRule extends Rule {
	
	public static function getUserId($user_hash) {
		$user_hash = StringHelper::normalizeString($user_hash);
	
		return self::getTextCode($user_hash);
	}
	
	public function getNetworkUserCount() {
		$sql = "select count(*) as Total from user";
		return $this->getData($sql);
	}
	
	public  function getNetworkUsersByBatch($data){
		$startLine = $data["startLine"];
		$bucket_size = $data["bucket_size"];
		
		$sql = "select id from user limit " .$startLine.",".$bucket_size;
		return $this->getData($sql);
	}
}
?>
