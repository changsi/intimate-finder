
<?php

/*Contains functions for:
 entity/content/item_of_day.php
 entity/admin/item_of_day/...
 */
require_once getRuleFilePath("facebook_app.ItemOfDayFacebookAppRule");

class ItemOfDayService {
	private $itemOfDayFacebookAppRule;
	
	public function __construct() {
		$this->itemOfDayFacebookAppRule = new ItemOfDayFacebookAppRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		$this->itemOfDayFacebookAppRule->setDBDriver($DBDriver);
	}
	
	//$data = array("day"=>02/6/2012);
	//to be used by entity/admin/item_of_day/list.php
	public function getAllItemsOfDay($data) {
		$param = array("day" => $data);
		return $this->itemOfDayFacebookAppRule->getAllItemsOfDay($param);
	}
	
	//$data = array("weekday" => "mon");
	//to be used by entity/content/item_of_day.php
	public function getItemOfDay($data) {
		//TODO
	}
	
	
	/* START INSERT FUNCTIONS */
	
	//$data = ?
	//to be used by entity/admin/item_of_day/insert.php
	public function insertItemOfDay($data) {
		return $this->itemOfDayFacebookAppRule->insertItemOfDay($data);
	}
	//$data = ?
	//to be used by entity/admin/item_of_day/insert.php
	public function deleteItemOfDay($data) {
		return $this->itemOfDayFacebookAppRule->deleteItemOfDay($data);
	}
	/* START UPDATE FUNCTIONS */
	
	//$data = ?
	//to be used by entity/admin/item_of_day/update.php
	public function updateItemOfDay($data) {
		//TODO
	}
	
	//$data = array("day"=>02/6/2012);
	//to be used by entity/admin/item_of_day/list.php
	public function getAllItemsForTribe($data) {
		return $this->itemOfDayFacebookAppRule->getAllItemsForTribe($data);
	}
}
?>
