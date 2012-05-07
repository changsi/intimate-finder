<?php

require_once getRuleFilePath("facebook_app.LocationFacebookAppRule");


require_once getLibFilePath("io.FileHandler");


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
	
	
	
	public function insertLocation($data){
		return $this->locationFacebookAppRule->insertLocation($data);
	}
}
?>
