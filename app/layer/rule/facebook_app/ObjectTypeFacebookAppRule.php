<?php
require_once getRuleFilePath("Rule");

class ObjectTypeFacebookAppRule extends Rule {
	

	public function getContentTypes() {
		$sql = "select * from object_type";
		
		return $this->getData($sql);
	}
	
	
	
}
?>
