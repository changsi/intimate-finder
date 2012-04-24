<?php
require_once getRuleFilePath("Rule");

class LocationFacebookAppRule extends Rule {

	public function insertLocation($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp

		$sql = "insert into location (location_id, name, latitude, longitude, picture_url, address, description) values ("  . $data["location_id"] .
		", '" . $data["name"] . "', ".$data['latitude'].", ".$data['longitude'].", '".$data['picture_url']."', '".$data['address']."', '".$data['description']
		."') ON DUPLICATE KEY UPDATE name=VALUES(name), latitude=values(latitude), longitude=values(longitude), picture_url=values(picture_url), 
		address=values(address), description=values(description)";
		//echo "\n\n\n". $sql."\n\n\n" ;
		return $this->setData($sql);
	}


}


?>