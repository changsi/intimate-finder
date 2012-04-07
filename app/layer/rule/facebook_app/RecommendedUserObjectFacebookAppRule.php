<?php
require_once getRuleFilePath("Rule");

class RecommendedUserObjectFacebookAppRule extends Rule {
	
	public function insertRecommendedUserContent($data) {
		$sql = "insert into recommended_user_object values (".$data['network_user_id'].", ".$data['object_type_id'].", ".$data['object_id'].", CURRENT_TIMESTAMP )";
		
		return $this->setData($sql);
	}
	
	
	public function getRecommendedUserContent($data) {
		$sql = "select * from recommended_user_object ruo inner join object o on (ruo.object_id=o.object_id and ruo.object_type_id=o.object_type_id) where network_user_id=".$data['network_user_id']." order by ruo.object_type_id " ;

		return $this->getData($sql);
	}
	
}
?>
