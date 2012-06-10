<?php


require_once getRuleFilePath("facebook_app.ObjectFacebookAppRule");




class ObjectService {
	private $objectFacebookAppRule;


	public function __construct() {
		$this->objectFacebookAppRule = new ObjectFacebookAppRule();
	}

	public function setDBDriverForLiveSystem($DBDriver) {
		$this->objectFacebookAppRule->setDBDriver($DBDriver);

	}

	//{"location_id"=>11111111}
	public function getObjectByID($data){
		return $this->objectFacebookAppRule->getObjectByID($data);
	}

	public function insertObject($data){
		return $this->objectFacebookAppRule->insertObject($data);
	}
}


?>