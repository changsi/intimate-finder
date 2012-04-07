<?php
require_once getRuleFilePath("facebook_app.NetworkPostUrlFacebookAppRule");
require_once getLibFilePath("io.FileHandler");

class FacebookService {
	private $networkPostUrlSpiralRule;
	
	public function __construct() {
		$this->networkPostUrlFacebookAppRule = new NetworkPostUrlFacebookAppRule();
	}
	
	public function setDBDriverForPlatform($DBDriver) {
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->networkPostUrlFacebookAppRule->setDBDriver($DBDriver);
	}
	
	//get unprocessed url data for core-platform script
	//$data = array("start" => 0, "limit" => 100);
	public function getUrlsForCP($data) {
		return $this->networkPostUrlFacebookAppRule->getUrlsForCP($data);
	}
	
	//get unprocessed url count
	public function getUserUrlsCountForCP() {
		$count = 0;
		$result = $this->networkPostUrlFacebookAppRule->getUserUrlsCountForCP();
		
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		
		return $count;
	}
	
	//get url object which has empty real urls
	public function getEmptyRealUrls($data) {
		return $this->networkPostUrlFacebookAppRule->getEmptyRealUrls($data);
	}
	
	//update real url
	//$data = array("real_url" => "http://www.test.com", "network_post_id" => "1", "url_id" => "11112")
	public function updateUserNetworkPostUrlRealUrl($data) {
		$param = array (
			"network_id" => 1,
			"real_url" => $data["real_url"], 
			"network_post_id" => $data["network_post_id"], 
			"url_id" => $data["url_id"]
		);
		
		return $this->networkPostUrlFacebookAppRule->updateUserNetworkPostUrlRealUrl($param);
	}

	
	//save url data file for core-platform script
	//$data = array("filepath" => "/home/twitter_urls_1.txt" ,"start" => 0, "limit" => 100);
	public function saveUrlDataForCP($data) {
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler->open("w+");
		
		$urls = $this->getUrlsForCP($data);
		
		if(!empty($urls)) {
			foreach($urls as $url){
				$url_str = $url["real_url"];
				if(trim($url_str) == "") {
					$url_str = $url["url"];
				}
				
				$item = $url["network_id"].$url["network_user_id"] . "\t" . $url["network_post_id"] . "\t" . $url_str . "\t" . $url["created_date"] . "\t" . $url["url_id"] . "\n"; 
				
				$fileHandler->write($item);
			}
		}
		
		$fileHandler->close();
	}
	
	//update url's control flag from file
	//$data = array("control_flag" => 1, "filepath" => "/home/url1.txt");
	public function updateNetworkPostUrlControlFlagFromFile($data) {
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler = $fileHandler->open("r+");
		
		if($fileHandler) {
			while ( ($line = fgets($fileHandler)) !== false) {
				$row = explode("\t", $line);
				
				$param = array(
					"network_id" => 2, //Facebook
					"control_flag" => $data["control_flag"],
					"network_post_id" => $row[1],
					"url_id" => $row[4]
				);
				
				$this->networkPostUrlFacebookAppRule->updateUserNetworkPostUrlControlFlag($param);
			}
		}
	}	
}
?>
