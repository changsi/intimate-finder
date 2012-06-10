<?php

require_once getRuleFilePath("facebook_app.LocationFacebookAppRule");




class LocationService {
	private $locationFacebookAppRule;
	
	
	public function __construct() {
		$this->locationFacebookAppRule = new LocationFacebookAppRule();
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->locationFacebookAppRule->setDBDriver($DBDriver);
		
	}
	
	public function getAllLocation(){
		return $this->locationFacebookAppRule->getAllLocation();
	}
	
	//{"location_id"=>11111111}
	public function getLocationByID($data){
		return $this->locationFacebookAppRule->getLocationByID($data);
	}
	
	//{"location_ids"=>"1111111,2222222,333333,444444,555555"}
	public function getLocationsByIDS($data){
		return $this->locationFacebookAppRule->getLocationsByIDS($data);
	}
	
	
	
	public function insertLocation($data){
		return $this->locationFacebookAppRule->insertLocation($data);
	}
}
?>
