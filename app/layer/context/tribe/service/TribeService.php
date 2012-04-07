<?php
/*
Contains functions for:
- entity/tribe/get_all_tribes.php
- entity/tribe/get_user_tribes.php => getTribeByUser
- entity/admin/tribe/...
*/
require_once getRuleFilePath("core_platform.DpCPRule");
require_once getRuleFilePath("core_platform.DpToCategoryCPRule");
require_once getRuleFilePath("facebook_app.TribeFacebookAppRule");
require_once getRuleFilePath("core_platform.DpToDpCPRule");
require_once getRuleFilePath("core_platform.DpToUserCPRule");
require_once getRuleFilePath("facebook_app.UserTribeFacebookAppRule");
require_once getRuleFilePath("facebook_app.TribeCategoryFacebookAppRule");

class TribeService {
	private $dpCPRule;
	private $dpToCategoryCPRule;
	private $tribeFacebookAppRule;
	private $dpToDpCPRule;
	private $dpToUserCPRule;
	private $userTribeFacebookAppRule;
	private $tribeCategoryFacebookAppRule;
	
	public function __construct() {
		$this->dpCPRule = new DpCPRule();
		$this->dpToCategoryCPRule = new DpToCategoryCPRule();
		$this->tribeFacebookAppRule = new TribeFacebookAppRule();
		$this->dpToDpCPRule = new DpToDpCPRule();
		$this->dpToUserCPRule = new DpToUserCPRule();
		$this->userTribeFacebookAppRule = new UserTribeFacebookAppRule();
		$this->tribeCategoryFacebookAppRule = new TribeCategoryFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->dpCPRule->setDBDriver($DBDriver);
		$this->dpToCategoryCPRule->setDBDriver($DBDriver);
		$this->dpToDpCPRule->setDBDriver($DBDriver);
		$this->dpToUserCPRule->setDBDriver($DBDriver);
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->tribeFacebookAppRule->setDBDriver($DBDriver);
		$this->userTribeFacebookAppRule->setDBDriver($DBDriver);
		$this->tribeCategoryFacebookAppRule->setDBDriver($DBDriver);
	}
	
	/* START GET FUNCTIONS */
	
	//$data = [ "tribe_name" => Giants];
	//is used by entity/tribe/get_all_tribes.php and entity/admin/tribe/update.php
	public function getTribeByName($data) {
		return $this->tribeFacebookAppRule->getTribeByName($data);
	}

	//to be used by entity/tribe/get_all_tribes.php and entity/admin/tribe/list.php
	public function getAllTribes() {
		return $this->tribeFacebookAppRule->getAllTribes();
	}
	
	
	//$data = array("tribe_id" => 1232);
	//tribe_id is from the CP its the manual dp id
	//to be used by entity/tribe/get_user_tribes.php
	public function getTribeInfoById($data) {
		return $this->tribeFacebookAppRule->getTribeInfoById($data);
	}
	
	//$data = array(network_id => 2, "network_user_id'" => 1232);
	//this function gets the manual dps from CP based on the user_id, which is from the CP and then get the tribe info related to
	//if the tribe doesnt exist in live db it wont return anything
	//to be used by entity/tribe/get_user_tribes.php
	public function getTribesByUserId($data) {
		$hash = isset($data['hash_user_id']) ? $data['hash_user_id'] : IdService::getUserId($data['network_id'].$data['network_user_id']);
		$data = array("user_id"=> $hash);
		$dp_list = $this->dpToUserCPRule->getDPsByUser($data);
		print_r($dp_list);
		$result = array();
		if(!empty($dp_list)){
			$dp_ids = "";
			foreach($dp_list as $dp){
				if(isset($dp['dp_id'])){
					$dp_ids .= $dp['dp_id'].',';
				}
			}
			$length = strlen($dp_ids);
			$data = array(
				"dp_ids" => substr($dp_ids,0,$length-1),
				"MIN_AFFINITY_TRIBE_DP" => MIN_AFFINITY_TRIBE_DP
			);
			$tribe_ids = $this->dpToDpCPRule->getTribesByDP($data);
			if(!empty($tribe_ids)){
				foreach($tribe_ids as $tribe_id){
					if(isset($tribe_id['dp_id'])){
						$data = array('tribe_id'=>$tribe_id['dp_id']);
						$tribe = $this->tribeFacebookAppRule->getTribeInfoById($data);
						if(isset($tribe[0])){
							$result[] = $tribe[0];
						}
					}
				}
				return $result;
			}
			
		}
		return $result;
		
	}
	
	//$data = array(network_id => 2, "network_user_id'" => 1232);
	//this function gets the tribe the user is affected to, from LIVE system
	public function getTribeByNetworkUserId ($data) {
		return $this->userTribeFacebookAppRule->getTribeByNetworkUserId($data);
	}
	
	
	//-- DEPRECATED 
	//$data = array("tribe_id" => 12323);
	//tribe_id is the manual_dp_id from the CP
	//to be used by entity/admin/tribe/get_related_users.php
	public function getTribeUsers($data) {
		return $this->dpToDpCPRule->getTribeUsers($data);
	}
	
	//$data = array("tribe_id" => 12323);
	//tribe_id is the manual_dp_id from the CP, gets the user from user_tribe table
	//to be used by entity/admin/tribe/get_tribe_users.php
	public function getTribeUsersInLive($data) {
		return $this->userTribeFacebookAppRule->getTribeUsersInLive($data);
	}
	
	//$data = array("tribe_id" => 12323);
	//tribe_id is the manual_dp_id from the CP
	//to be used by entity/admin/tribe/get_related_dps.php
	public function getTribeDPs($data) {
		return $this->dpToDpCPRule->getTribeDPs($data);
	}
	
	//$data = array("tribe_id" => 12323, "network_user_id" => 3131, "network_id" => 2 [, "limit" => 5]);
	//get the friends ids who are in the same tribe
	public function getUserFriendsInThatTribe($data) {
		return $this->userTribeFacebookAppRule->getUserFriendsInThatTribe($data);
	}
	
	//$data = array("tribe_id" => 234234234, "category_type" => 'profile');
	//tribe_id is from CP
	//to be used by entity/admin/tribe/get_tribe_categories.php
	public function getTribeCategories ($data) {
		$categories = $this->tribeCategoryFacebookAppRule->getTribeCategories($data);
		$category_type = isset($data['category_type']) ? $data['category_type'] : 'none';

		switch ($category_type) {
			case 'url':
				$alchemyCategories = unserialize(ALCHEMY_CATEGORIES);
				$profileCategories = unserialize(PROFILE_CATEGORIES);
				foreach ($categories as $key => $category) {
					if (array_key_exists($category['category_id'],$alchemyCategories)) {
						$categories[$key]['category_type'] = 'url';
						$categories[$key]['name'] = $alchemyCategories[$category['category_id']];// put the name of the alchemy category in the result array (was null previously)
					}
					else if (array_key_exists($category['category_id'],$profileCategories)) {
						unset($categories[$key]);
					}
				}
			break;
			case 'profile':
				$alchemyCategories = unserialize(ALCHEMY_CATEGORIES);
				$profileCategories = unserialize(PROFILE_CATEGORIES);
				foreach ($categories as $key => $category) {
					if (array_key_exists($category['category_id'],$profileCategories)) {
						$categories[$key]['category_type'] = 'profile';
						$categories[$key]['name'] = $profileCategories[$category['category_id']];// put the name of the profile category in the result array (was null previously)
					} 
					else if (array_key_exists($category['category_id'],$alchemyCategories)) {
						unset($categories[$key]);
					}
				}
			break;
			case 'none':
				$alchemyCategories = unserialize(ALCHEMY_CATEGORIES);
				$profileCategories = unserialize(PROFILE_CATEGORIES);
				foreach ($categories as $key => $category) {
					if (array_key_exists($category['category_id'],$alchemyCategories)) {
						$categories[$key]['category_type'] = 'url';
						$categories[$key]['name'] = $alchemyCategories[$category['category_id']];// put the name of the alchemy category in the result array (was null previously)
					}
					else if (array_key_exists($category['category_id'],$profileCategories)) {
						$categories[$key]['category_type'] = 'profile';
						$categories[$key]['name'] = $profileCategories[$category['category_id']];// put the name of the profile category in the result array (was null previously)
					}
				}
			break;
		}

		return $categories;
	}
	
	//$data = ["tribes" => [2,3,4], "network_user_id" =>1321654 , "network_id"=>2];
	public function getOtherTribesInfoAndFriendsInside ($data) {
		
		$tribes = array();
		$results = $this->tribeFacebookAppRule->getOtherTribesInfo($data);
		
		foreach ($results as $key => $result ) {
			$tribes[$key]["tribe_id"] = $result["id"];
			
			//store info of the tribe
			$tribes[$key]['info'] = $result;
			
			$param = array (
				"tribe_id" => $result["id"],
				"network_user_id" => $data["network_user_id"],
				"network_id" => $data["network_id"]
			);
			
			//friends of the user in that tribe
			$tribes[$key]['friends'] = $this->getUserFriendsInThatTribe($param);
			$friends = array();
			foreach ($tribes[$key]['friends'] as $friend) {
				$friends[] = $friend['name']; 
			}
			if (!empty($friends)) {
				$friendsStr = implode(",", $friends);
				$tribes[$key]['friends_list'] = $friendsStr;
			}
		}
		return $tribes;
	}
	
	/* START INSERT FUNCTIONS */
	
	/*$data = array("name" => "", "description" => "", categories => array(
		112323 => 0.3,
		5577=> 0.6,
		<category_id> => <affinity>,
		profile_categories =>  array(
		9995999 => 1,
		9996999=> 1,
		<category_id> => <affinity>,
	));*/
	//to be used by entity/admin/tribe/insert.php
	//NO UPDATE, this function only inserts in DB
	public function insertTribe($data) {
		if (isset($data['name']) && isset($data['categories']) && !empty($data['categories'])) {
			//DEPRECATED we are now checking by ajax when the user is filling if there is already an existing tribe with the same name
			/*$dp = $this->dpCPRule->getManualDpByName($data);//check if there is a manual dp with the same name..
			if (!empty($dp)) {// in case the manual dp already exists, we update it
				$data['dp_id'] = $dp[0]['id'];
				$this->updateTribe($data);
				return;
			}*/
			
			$bool = $this->dpCPRule->insertManualDp($data); //insert manual dp into dp table
			if ($bool) {
				$dpId = $this->dpCPRule->getManualDpByName($data);//get the manual dp id just created
				if (isset($dpId[0]['id'])) {
					$data['dp_id'] = $dpId[0]['id'];
					$this->dpToCategoryCPRule->insertManualDpCategories($data); // insert the dp categories in CP db only url categories
					$this->tribeCategoryFacebookAppRule->insertTribeCategories($data); // insert the tribe in live db url categories + profile categories
					$this->tribeFacebookAppRule->insertTribe($data); // insert the dp in live system
				}
			}
		}
	}
	
	/* START UPDATE FUNCTIONS */
	
	/*$data = array("tribe_id" => 12323, "name" => "", "description" => "", categories => array(
		112323 => 0.3,
		5577=> 0.6,
		<category_id> => <affinity>,
		profile_categories =>  array(
		9995999 => 1,
		9996999=> 1,
		<category_id> => <affinity>,
	));*/
	//tribe_id is the manual_dp_id from the CP
	//to be used by entity/admin/tribe/update.php
	public function updateTribe($data) {
		if (isset($data['name']) && isset($data['dp_id']) && isset($data['categories']) && !empty($data['categories'])) {
			$this->dpCPRule->updateManualDp($data);
			$this->dpToCategoryCPRule->deleteDpCategories($data); // delete the categories in CP
			$this->tribeCategoryFacebookAppRule->deleteTribeCategories($data); // delete the categories in Live
			$this->dpToCategoryCPRule->insertManualDpCategories($data); // insert the dp categories
			$this->tribeCategoryFacebookAppRule->insertTribeCategories($data); // insert the tribe categories in live db url categories + profile categories
			$this->tribeFacebookAppRule->insertTribe($data); // insert the dp in live system
		}
	}
	
	
	//$data = [network_id => 2, network_user_id => 524634, tribe_id =>35]
	//insert a row in user tribe table in the live system
	public function saveUserTribe($data) {
		return $this->userTribeFacebookAppRule->saveUserTribe($data);
	}
	
	
}
?>
