<?php
require_once getRuleFilePath("Rule");

class ItemOfDayFacebookAppRule extends Rule {
	
	public function getAllItemsOfDay($data) {
			$day = $this->getDateFromString($data['day']);
			$sql= 'select tr.name as tribe_name,it.tribe_id, o.name from tribe tr,item_of_day it inner join object o on (it.object_id =o.object_id and it.object_type_id = o.object_type_id) where it.date="'.$day.'" and tr.id = it.tribe_id';
			//echo $sql;
			return $this->getData($sql);
			
	}
	
	public function getAllItemsForTribe($data){
			$day = $this->getDateFromString($data['day']);
			$tribe_id = $data["tribe_id"]; //tribe_id will be used later, for now I am fetching all the objects
			$sql= 'select distinct o.object_type_id,o.object_id, o.name from object o';
			return $this->getData($sql);
	}
	
	public function insertItemOfDay($data){
		$object_id = $data["object_id"] ; 
		$object_type_id = $data["object_type_id"];
		$tribe_id = $data["tribe_id"];
		$date =$this->getDateFromString( $data["date"]); 
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$sql = "insert into item_of_day (object_type_id,object_id,tribe_id,date,created_date) values (" . $object_type_id . "," . $object_id . "," . $tribe_id . ",". "'$date'" .","."'$created_date'".") ON DUPLICATE KEY UPDATE object_type_id = ". $object_type_id . ",object_id = ".$object_id;
		return $this->setData($sql); 
	}
	
	public function deleteItemOfDay($data){
		
		$tribe_id = $data["tribe_id"];
		$date =$this->getDateFromString( $data["date"]); 
		
		$sql = "delete from item_of_day where tribe_id = " . $tribe_id . " and date = ". "'$date'"; 
		//echo $sql;
		return $this->setData($sql); 
	}
}
?>
