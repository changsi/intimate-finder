<?php
require_once getRuleFilePath("Rule");

class UserCategoryCPRule extends Rule {
	
	public function getUserCategories($data) {
		$sql = "SELECT * FROM user_category uc left join word_set ws on uc.category_id = ws.id where user_id = ".$data["user_id"]." order by affinity desc";

		return $this->getData($sql);
	}
	
}
?>
