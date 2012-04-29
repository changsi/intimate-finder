<?php

require_once getRuleFilePath("facebook_app.DownloadingProgressFacebookAppRule");


require_once getLibFilePath("io.FileHandler");


class UserProgressionService {
	private $downloadingProgressFacebookAppRule;


	public function __construct() {
		$this->downloadingProgressFacebookAppRule = new DownloadingProgressFacebookAppRule();
	}

	public function setDBDriverForCorePlatform($DBDriver) {

	}

	public function setDBDriverForLiveSystem($DBDriver) {
		$this->downloadingProgressFacebookAppRule->setDBDriver($DBDriver);

	}

	public function insertUserProgress($data){
		return $this->downloadingProgressFacebookAppRule->insertProgress($data);
	}

	public function deleteUserProgress($data){
		
		return $this->downloadingProgressFacebookAppRule->deleteProgress($data);
	}
	
	public function getUserProgress($data){
	
		return $this->downloadingProgressFacebookAppRule->getProgress($data);
	}
}

?>