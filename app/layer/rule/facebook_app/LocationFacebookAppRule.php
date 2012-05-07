<?php
require_once getRuleFilePath("Rule");

class LocationFacebookAppRule extends Rule {

	public function insertLocation($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp

		$sql = "insert into location (location_id, name, latitude, longitude, picture_url, street,city,state, country, zip, description) values ("  .
		 $data["location_id"] .", '" .  mysql_real_escape_string($data["name"]) . "', ".$data['latitude'].", ".$data['longitude'].", '".$data['picture_url'].
		 "', '".mysql_real_escape_string($data['street']) ."', '". mysql_real_escape_string($data['city']) ."', '".
		  mysql_real_escape_string($data['state']) ."', '".mysql_real_escape_string($data['country'])."', '".$data['zip']."', '". 
		  mysql_real_escape_string($data['description'])
		."') ON DUPLICATE KEY UPDATE name=VALUES(name), latitude=values(latitude), longitude=values(longitude), picture_url=values(picture_url), 
		street=values(street), city=values(city), state=values(state), country=values(country), zip=values(zip),description=values(description)";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->setData($sql);
	}
	
	public function getAllLocation(){
		$sql = "select location_id, latitude, longitude from location  ";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->getData($sql);
	}


}


?>