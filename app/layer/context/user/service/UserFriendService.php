<?php
require_once getRuleFilePath("core_platform.UserToUserCPRule");
require_once getRuleFilePath("facebook_app.NetworkFriendFacebookAppRule");
require_once getRuleFilePath("facebook_app.UserTribeFacebookAppRule");
require_once getRuleFilePath("facebook_app.UserFriendSimilarityFacebookAppRule");
require_once getRuleFilePath("core_platform.DpToUserCPRule");
require_once getLibFilePath("io.FileHandler");

class UserFriendService {
	private $userToUserCPRUle;
	private $networkFriendFacebookAppRule;
	private $userTribeFacebookAppRule;
	private $dpToUserCPRule; 
	private $userFriendSimilarityFacebookAppRule;
		
	public function __construct() {
		$this->userToUserCPRUle = new UserToUserCPRUle();
		$this->networkFriendFacebookAppRule = new NetworkFriendFacebookAppRule();
		$this->userTribeFacebookAppRule= new UserTribeFacebookAppRule();
		$this->dpToUserCPRule = new DpToUserCPRule(); 
		$this->userFriendSimilarityFacebookAppRule = new UserFriendSimilarityFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->userToUserCPRUle->setDBDriver($DBDriver);
		$this->dpToUserCPRule->setDBDriver($DBDriver);		
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->networkFriendFacebookAppRule->setDBDriver($DBDriver);
		$this->userTribeFacebookAppRule->setDBDriver($DBDriver);
		$this->userFriendSimilarityFacebookAppRule->setDBDriver($DBDriver);
	}
	
	public function insertUserFriendSimilarity($data){
		return $this->userFriendSimilarityFacebookAppRule->insertUserFriendSimilarity($data);
	}
	
	public function getTopFriends($data){
		return $this->getTopFriends($data);
	}
	
	//$data = array("user_id" => 123, "friend_ids" => array(1,2,3));
	public function getUserFriendAffinity($data) {
		return $this->userToUserCPRUle->getUserFriendAffinity($data);
	}
	
	//get data from user_user table
	//$data = array("user_id" => 123, "start" => 0, "limit" => 10);
	public function getUserToUserAffinity($data) {
		return $this->userToUserCPRUle->getUserToUserAffinity($data);
	}
	
	//get data from user_user table
	//$data = array("user_id" => 123, "category_id" => 32345, "start" => 0, "limit" => 10);
	public function getUserToUserAffinityByCategory($data) {
		return $this->userToUserCPRUle->getUserToUserAffinityByCategory($data);
	}
	
	//$data = array( array("ass" => array("affinity" => 0.2, "type" => 1), "rt" => array("affinity" => 0.2, "type" => 1)), array("ass" => array("affinity" => 0.1, "type" => 0), "yhbv" => array("affinity" => 0.`, "type" => 0)) );
	public function getCalculatedUserFriendAffinity($data) {
		$min = 0;
		$max = 0;
		
		$repeated_categories = array();
		
		for ($i = 0; $i < count($data); $i++) {
			$user_categories_i = $data[$i];
			
			for ($j = $i + 1; $j < count($data); $j++) {
				$user_categories_j = $data[$j];
				
				foreach ($user_categories_i as $category_id => $value) {
					$category_weight = $value["affinity"];
				
					if (!isset($user_categories_j[$category_id]["affinity"])) {
						$max += $user_categories_i[$category_id]["affinity"];
					}
					else {
						$min += sqrt($user_categories_i[$category_id]["affinity"] * $user_categories_j[$category_id]["affinity"]);
						$max += max($user_categories_i[$category_id]["affinity"], $user_categories_j[$category_id]["affinity"]);
					}
					
					$repeated_categories[$category_id] = 1;
				}
				
				foreach ($user_categories_j as $category_id => $value) {
					$category_weight = $value["affinity"];
					if (!isset($repeated_categories[$category_id])) {
						$max += $user_categories_j[$category_id]["affinity"];
					}
				}
			}
		}
		
		$user_to_user_affinity = $max > 0 ? ($min / $max) : 0;
		
		return $user_to_user_affinity > 0 ? $user_to_user_affinity : 0;
	}
	
	
	//$data = array("network_user_id" => "2342342432", "friend_ids" => array("13423423423", "2342354656"))
	public function saveNetworkFriends($data) {
		foreach($data["friend_ids"] as $friend_id) {
			$param = array (
				"network_id" => 1,
				"network_user_id_from" => $data["network_user_id"], 
				"network_user_id_to" =>$friend_id
			);
			$this->networkFriendFacebookAppRule->insertUserNetworkFriend($param);
		}
	}
	
	//$data = array("start" => "0", "limit" => "100")
	public function getAllNetworkFriendsMapping($data) {
		return $this->networkFriendFacebookAppRule->getAllNetworkFriendsMapping($data);
	}
	
	//get unprocessed friends count
	public function getAllNetworkFriendsMappingCount() {
		$count = 0;
		$result = $this->networkFriendFacebookAppRule->getAllNetworkFriendsMappingCount();
		
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		
		return $count;
	}
	
	//$data = array("start" => "0", "limit" => "100", "filepath" => "/home/friend1.txt")
	public function saveAllNetworkFriendsMappingForCP($data) {
		$friends = $this->getAllNetworkFriendsMapping($data);
		
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler->open("w+");
		
		foreach($friends as $friend){
			$item = $friend["network_id"].$friend["network_user_id_from"] . "\t" . $friend["network_id"].$friend["network_user_id_to"] . "\n"; 
			
			$fileHandler->write($item);
		}
		
		$fileHandler->close();
	}
	
	//$data = array("start" => "0", "limit" => "100")
	public function getProcessedUserIds($data) {
		return $this->networkFriendFacebookAppRule->getProcessedUserIds($data);
	}
	
	//get processed user id count
	public function getProcessedUserIdsCount() {
		$count = 0;
		$result = $this->networkFriendFacebookAppRule->getProcessedUserIdsCount();
		
		if(isset($result[0])) {
			$count = $result[0]["count"];
		}
		
		return $count;
	}
	
	//$data = array("start" => "0", "limit" => "100", "filepath" => "/home/friend1.txt")
	public function saveProcessedUserIdsForCP($data) {
		$friends = $this->getProcessedUserIds($data);
		
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler->open("w+");
		
		foreach($friends as $friend){
			$item = $friend["network_id"].$friend["network_user_id_from"] . "\n"; 
			
			$fileHandler->write($item);
		}
		
		$fileHandler->close();
	}
	
	//for updating control flag
	//$data = array("control_flag" => 1, "filepath" => "/home/friend1.txt")
	public function updateNetworkFriendControlFlagByMainUserFromFile($data) {
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler = $fileHandler->open("r+");
		
		if($fileHandler) {
			while ( ($from_id = fgets($fileHandler)) !== false) {
				echo "$from_id\n";
				$param = array(
					"network_id" => 2, 
					"control_flag" => $data["control_flag"], 
					"network_user_id_from" => $from_id
				);
				
				$this->networkFriendFacebookAppRule->updateUserNetworkFriendControlFlagByMainUser($param);
			}
		}
	}
	
	public function deleteNetworkFriendControlFlagByMainUserFromFile($data) {
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler = $fileHandler->open("r+");
		
		if($fileHandler) {
			while ( ($from_id = fgets($fileHandler)) !== false) {
				//echo "$from_id\n";
				$from_id = substr($from_id, 1);
				$param = array(
					"network_id" => 2, 
					"control_flag" => $data["control_flag"], 
					"network_user_id_from" => $from_id
				);
				
				$this->networkFriendFacebookAppRule->deleteNetworkFriendControlFlagByMainUserFromFile($param);
			}
		}
	}
	
	//update Network Friends' control flags from input file
	//$data = array("control_flag" => 1, "filepath" => "/home/friend1.txt");
	public function updateUserNetworkFriendControlFlagFromFile($data) {
		$fileHandler = new FileHandler($data["filepath"]);
		$fileHandler = $fileHandler->open("r+");
		
		if($fileHandler) {
			while ( ($line = fgets($fileHandler)) !== false) {
				$row = explode("\t", $line);
				$row[0] = substr($row[0], 1);
				$row[1] = substr($row[1], 1);
				$param = array(
					"network_id" => $data["network_id"], 
					"control_flag" => $data["control_flag"], 
					"network_user_id_from" => $row[0], 
					"network_user_id_to" => $row[1]
				);
				
				$this->networkFriendFacebookAppRule->updateUserNetworkFriendControlFlag($param);
			}
		}
	}
	
	//get the user friends that are in the system but dont have a tribe
	//$data = [network_id => 2, network_user_id => 13245 [, limit => 5]];
	public function getUserFriendsThatDontHaveATribeInLive ($data) {
		$results = $this->userTribeFacebookAppRule->getUserFriendsThatDontHaveATribeInLive($data);
		$friendsIds = array();
		if (!empty($results)) {
			foreach ($results as $row) {
				$friendsIds[] = $row["network_user_id"];
			}
		}
		
		return $friendsIds;
	}
	
	//from an array of friends it fetches all the friends where the highest affinded dp is the same as the current user
	//$data = ["tribe_id" => 515 , "friends" => array(1,2,3,4)] ;
	public function getFriendsHavingTheSameTribeInCP($data) {
		//replace the network_user_ids of the friends by the hashs
		foreach ($data["friends"] as $key => $friendId) {
			$data["friends"][$key] = IdService::getUserId('2'.$friendId);
		}
		
		$data["affinity_threshold"] = MIN_AFFINITY_DP_USER;
		
		return $this->dpToUserCPRule->getFriendsHavingTheSameTribeInCP($data);
	}
}
?>
