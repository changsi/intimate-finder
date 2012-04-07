<?php
require_once getRuleFilePath("Rule");
require_once getLibFilePath("util.StringHelper");

class ObjectFacebookAppRule extends Rule {
	
	public function insertObject($data) {
		
		
		
		$sql = "insert ignore into object (object_id, name, category, picture_url, link, likes, website, description) values(".$data["object_id"].", '".$this->addSlashes($data["name"])."', '".$data["category"]."' , '".$data["picture_url"]."' , '".mysql_real_escape_string($data['link'])."', ".$data['likes'].", '".$data['website']."', '".mysql_real_escape_string($data['description'])."')";

		return $this->setData($sql);
	}
	
	public function insertObjectUrl($data) {
		$sql = "insert into object_url (object_id, url_id, url) values(".$data["object_id"].",".$data["url_id"].",'".$this->addSlashes($data["url"])."')";
		
		return $this->setData($sql);
	}
	
	public function insertManualObject($data) {
		$name = StringHelper::normalizeString($this->addSlashes($data["name"]));
		$url = $this->addSlashes($data["url"]);
		$id = StringHelper::getHashCodePositive($url);
		$author = StringHelper::normalizeString ($this->addSlashes($data["author"]));
		$genre = StringHelper:: normalizeString($this->addSlashes($data["genre"]));
		$genre2 = StringHelper::normalizeString($this->addSlashes($data["genre2"]));
		$object_type_id = $data["object_type_id"]; 
		$network_object_id = $data["network_object_id"];
		
		$sql = "insert ignore into object (object_id, object_type_id, name, network_object_id, url, author, genre, genre2) values(".$id.", ".$object_type_id.", '".$name."', ".$network_object_id.", '".$url."', '".$author."', '".$genre."', '".$genre2."')";
			return $this->setData($sql);
	}
	
	public function getObjectByID($data){
		$sql = "SELECT * FROM object WHERE object_id = ".$data["object_id"].";";
		return $this->getData($sql);
	}
	
	public function getObjectByIDs($data){
		$sql = "SELECT * FROM object WHERE object_id in( ".implode(',', $data["object_ids"])." ) ;";
		return $this->getData($sql);
	}

	
}
?>
