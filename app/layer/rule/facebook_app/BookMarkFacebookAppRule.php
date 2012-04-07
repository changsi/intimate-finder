<?php
require_once getRuleFilePath("Rule");

class BookMarkFaceBookAppRule extends Rule {
	
	
	public function insertBookMark($data) {
		$network_id = $data["network_id"];
		$network_user_id = $data["network_user_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		
		$sql = "insert into bookmark (network_id, network_user_id, object_type_id, object_id, created_date) values (" . $network_id . "," . $network_user_id . "," . $object_type_id . "," .$object_id. "," . "'$created_date'"  .")";
//		echo $sql;
		return $this->setData($sql); 
	}
	
	public function getBookMark($data) {
		$network_id = $data["network_id"];
		$network_user_id = $data["network_user_id"];

		$sql = "select o.object_type_id, o.object_id, o.name, o.url from object o, bookmark b where b.network_id =" . $network_id . " and b.network_user_id =" . $network_user_id." and o.object_type_id = b.object_type_id and o.object_id = b.object_id";
//		echo $sql;
		return $this->getData($sql); 
	}
	
	public function deleteBookMark($data) {
		$network_id = $data["network_id"];
		$network_user_id = $data["network_user_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$sql = "delete from bookmark where network_id =" . $network_id . " and network_user_id =" . $network_user_id. " and object_type_id = " . $object_type_id.  " and object_id = " . $object_id;
//		echo $sql;
		return $this->setData($sql); 
	}
	
	
}
?>
