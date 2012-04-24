<?php
/*
Contains functions for:
- entity/user/get_user_categories.php
*/
require_once getRuleFilePath("core_platform.UserCategoryCPRule");
require_once getRuleFilePath("facebook_app.UserNetworkDataFacebookAppRule");

class UserCategoryService {
	private $userCategoryCPRule;
	private $userNetworkDataFacebookAppRule;
	
	public function __construct() {
		$this->userCategoryCPRule = new UserCategoryCPRule();
		$this->userNetworkDataFacebookAppRule = new UserNetworkDataFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->userCategoryCPRule->setDBDriver($DBDriver);
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->userNetworkDataFacebookAppRule->setDBDriver($DBDriver);
	}
	
	/* START GET FUNCTIONS */
	
	//$data = array("user_id" => 13134214);
	//user_id is from CP
	//to be used by entity/user/get_user_categories.php
	public function getUserCategories($data) {
		if (isset($data['hash_user_id'])) {
			$data['user_id'] = $data['hash_user_id'];
		}
		$categories = $this->userCategoryCPRule->getUserCategories($data);
		$profileCategories = unserialize(PROFILE_CATEGORIES);
		foreach ($categories as $key => $category) {
			if (array_key_exists($category['category_id'],$profileCategories)) {
				$categories[$key]['name'] = $profileCategories[$category['category_id']];// put the name of the profile category in the result array (was null previously)
			}
		}
		return $categories;
	}
	
	//$data = [ "network_user_id" => 4351 , network_id => 2];
	//fetch the user profile info and match them to categories
	public function getUserProfileCategories($data) {
		$userProfileCategories = array();
		$profileCategories = unserialize(PROFILE_CATEGORIES);
		$profileCategories = array_flip($profileCategories);
		
		$result = $this->userNetworkDataFacebookAppRule->getUserNetworkProfile($data);
		$userInfo = $result[0];
		
		//age
		if ($userInfo['age'] > 0 && $userInfo['age'] < 14 ) {
			$userProfileCategories[] = $profileCategories['kid']; 
		}
		else if ($userInfo['age'] >= 14 && $userInfo['age'] < 18 ) {
			$userProfileCategories[] = $profileCategories['teenager']; 
		}
		else if ($userInfo['age'] >= 18 && $userInfo['age'] < 30 ) {
			$userProfileCategories[] = $profileCategories['adult']; 
		}
		else if ($userInfo['age'] >= 30 && $userInfo['age'] < 37 ) {
			$userProfileCategories[] = $profileCategories['experienced']; 
		}
		else if ($userInfo['age'] >= 37) {
			$userProfileCategories[] = $profileCategories['old']; 
		}
		
		//gender
		switch ($userInfo['gender']) {
			case 1:
				$userProfileCategories[] = $profileCategories['male']; 
			break;
			case 2:
				$userProfileCategories[] = $profileCategories['female']; 
			break;
		}
		
		
		//relationship
		switch ($userInfo['name']) {
			case 'single':
				$userProfileCategories[] = $profileCategories['single']; 
			break;
			case 'couple':
				$userProfileCategories[] = $profileCategories['in_a_relationship']; 
			break;
			case 'engaged':
				$userProfileCategories[] = $profileCategories['engaged']; 
			break;
			case 'married':
				$userProfileCategories[] = $profileCategories['married']; 
			break;
			case 'complicated':
				$userProfileCategories[] = $profileCategories['its_complicated']; 
			break;
			case 'free':
				$userProfileCategories[] = $profileCategories['open']; 
			break;
			case 'widow':
				$userProfileCategories[] = $profileCategories['widowed']; 
			break;
			case 'separated':
				$userProfileCategories[] = $profileCategories['separated']; 
			break;
			case 'divorced':
				$userProfileCategories[] = $profileCategories['divorced']; 
			break;
			case 'civil_union':
				$userProfileCategories[] = $profileCategories['civil_union']; 
			break;
			case 'domestic_partnership':
				$userProfileCategories[] = $profileCategories['domestic_partnership']; 
			break;
		}
		
		//education
		//location
		
		return  $userProfileCategories;
		
		
	}
}
?>
