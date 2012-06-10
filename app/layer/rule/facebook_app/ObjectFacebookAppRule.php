<?php
require_once getRuleFilePath("Rule");

class ObjectFacebookAppRule extends Rule {

	public function insertObject($data) {

		$sql = "insert ignore into object (object_id, name, category, picture_url
		, link, likes, website, description) values(".$data["object_id"].", '".$this->addSlashes($data["name"]).
		"', '".$data["category"]."' , '".$data["picture_url"]."' , '".mysql_real_escape_string($data['link'])."', ".
		$data['likes'].", '".$data['website']."', '".mysql_real_escape_string($data['description'])."')";

		return $this->setData($sql);
	}

}


?>