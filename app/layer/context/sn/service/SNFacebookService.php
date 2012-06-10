<?php

require getLibFilePath("sn.platform.facebook.facebook");

class SNFacebookService {
	public $user;
	private $fbconfig;
	private $facebook;
	
	public function __construct() {
		$this->fbconfig['appid' ]     = '382809748430590';
    		$this->fbconfig['secret']     = 'fb789ce03dd7178aac5905b69a7a7b34';
    		$this->fbconfig['baseurl']    = 'http://cgp.com/intimate-finder/app/sn/facebook/login/';
    		$this->facebook = new Facebook(array(
      		'appId'  => 	$this -> fbconfig['appid'],
      		'secret' => 	$this -> fbconfig['secret'],
      		'cookie' => true
	    	));
    		//$this->user = $this->facebook->getUser();// You cannot do this
    		
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		
	}
	
	public function setAccessToken($data){
		$this->facebook->setAccessToken($data['access_token']);
		$this->user = $this->facebook->getUser();
	}
	
	/* START OTHERS FUNCTIONS */
	
	//$data = ?
	//To be used by entity/sn/facebook/login.php
	
	public function login() {
		$para = array(
                'scope'         => PERMISSION,
                'redirect_uri'  => 'http://cgp.com/intimate-finder/app/sn/facebook/login/'
            );
		$loginUrl   = $this->facebook->getLoginUrl($para);
		
		return $loginUrl;
		
		//TODO
	}
	
	public function getUserProfile($data) {
		if($this->user){
			$user_profile = $this->facebook->api('/'.$data["user_id"]);
			return $user_profile;
		}
	}

	
	public function getAccessToken() {
		if($this->user){
			$access_token = $this->facebook->getAccessToken();
			return $access_token;
		}
		
	}
		
	public function getUserId() {
		if($this->facebook->getUser()){
			$this->user = $this->facebook->getUser();
			return $this->user;
		}
		return false;
	}
	
	
	//$data = [user_id => 1565165]
	//get the user profile info, checkin and friends list
	public function getEverythingExceptFriendsCheckin($data) {
		if($this->user){
			$queries = array(
    				array('method' => 'GET', 'relative_url' => '/'.$data['user_id']),
    				array('method' => 'GET', 'relative_url' => '/'.$data['user_id'].'/checkins'),
    				
    				array('method' => 'GET', 'relative_url' => '/'.$data['user_id'].'/friends', "omit_response_on_success" => false, "name" => "get-friends")
    				
			);
			return $this->sendBatchRequests($queries,0);
		}
	}
	
	//$data = [start => 0, limit => 5 , friends => array(0 => 341542, 1 => 65443, 2 => 545524.....)]
	//Function that encapsulates requests to get the profile info and feeds for 5 friends equivalent to 45 requests
	public function getFriendProfileInfoAndCheckin ($data) {
		if($this->user){
			
			$start = $data['start'];
			$limit = $data['limit'];
			$friends = $data['friends'];
			
			$queries = array();
			for ($i = $start; $i < ($start+$limit); $i++) {
				$queryProfile = array('method' => 'GET', 'relative_url' => '/'.$friends[$i]);
				$queryCheckin = array('method' => 'GET', 'relative_url' => '/'.$friends[$i].'/checkins');
    				
				
				
				$queries[] = $queryProfile;
				$queries[] = $queryCheckin;
			}
			return $this->sendBatchRequests($queries,0);
		}
	}
	
	// by si chang
	public function getUserAndFriendsLikes($data){
		if($this->user){
			$queries = array(
					array('method'=>'GET', 'relative_url'=>'/'.$data['user_id'].'/likes'),
					array('method'=>'GET', 'relative_url'=>'/'.$data['user_id'].'/friends',"omit_response_on_success" => true,'name'=>'get-friends'),
					array('method'=>'GET', 'relative_url'=>'/likes?ids={result=get-friends:$.data.*.id}')
			);
			return $this->sendBatchRequests($queries,0);
		}
	}
	
	
	
	//send batch requests if FB api timeout we retry 4 times before returning false
	//$data is the array of queries to send
	public function sendBatchRequests($data, $tryCount) {
		$tryCount++;
		try {
			// regular execution continues.
			//echo count($data)."\n";
			
			$batchResponse = $this->facebook->api('?batch='.urlencode(json_encode($data)), 'POST');
			
			//Due to sometimes errors from Facebook in the response (Error_code = 1 "Unknown error occured")
			//we apply the same process of retrying for 4 times
			if (isset($batchResponse['error_code'])) {
				if ($tryCount > 20) {
					echo "Tried $tryCount times to fetch data but fail, now exiting.";
					return false;
				}
				echo "Error inside the response :";
				echo "Error code : ".$batchResponse['error_code']." - Message : ".$batchResponse['error_msg']." \n";
				sleep(1);
				echo "Trying again \n";
				$batchResponse = $this->sendBatchRequests($data,$tryCount);
			}
		} catch (Exception $e) {
			if ($tryCount > 20) {
				echo "Tried $tryCount times to fetch data but fail, now exiting.";
				return false;
			}
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			sleep(10);
			echo "Trying again \n";
			$batchResponse = $this->sendBatchRequests($data,$tryCount);
		}
		return $batchResponse;
	}
}
?>
