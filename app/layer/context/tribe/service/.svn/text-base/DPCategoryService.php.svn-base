<?php
/*
Contains functions for:
- entity/dp/get_dp_categories.php
*/

require_once getRuleFilePath("core_platform.DpToCategoryCPRule");

class DPCategoryService {
	private $dpToCategoryCPRule;
	
	public function __construct() {
		$this->dpToCategoryCPRule = new DpToCategoryCPRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->dpToCategoryCPRule->setDBDriver($DBDriver);
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		
	}
	
	/* START GET FUNCTIONS */
	
	//$data = array("dp_id" => 234234234);
	//dp_id is from CP
	//to be used by entity/dp/get_dp_categories.php
	public function getDPCategories($data) {
		$categories = $this->dpToCategoryCPRule->getDPCategories($data);
		$profileCategories = unserialize(PROFILE_CATEGORIES);
		foreach ($categories as $key => $category) {
			if (array_key_exists($category['category_id'],$profileCategories)) {
				$categories[$key]['name'] = $profileCategories[$category['category_id']];// put the name of the profile category in the result array (was null previously)
			}
		}
		return $categories;
	}
}
?>
