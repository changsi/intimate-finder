<?php
/*
Contains functions for:
- entity/dp/get_friends_dps.php
- entity/dp/get_user_dps.php
- entity/dp/get_users_by_dp.php
*/


require_once getRuleFilePath("core_platform.DpToUserCPRule");
require_once getRuleFilePath("core_platform.DpToDpCPRule");

class DPService {
	private $dpToUserCPRule;
	private $dpToDpCPRule;

	public function __construct() {
		$this->dpToUserCPRule = new DpToUserCPRule();
		$this->dpToDpCPRule = new DpToDpCPRule();
	}
	
	public function setDBDriverForCorePlatform($DBDriver) {
		$this->dpToUserCPRule->setDBDriver($DBDriver);
		$this->dpToDpCPRule->setDBDriver($DBDriver);
	}
	
	public function setDBDriverForLiveSystem($DBDriver) {
		
	}
	
	/* START GET FUNCTIONS */
	
	//$data = array("user_id" => 234234234);
	//user_id is from CP
	//to be used by entity/dp/get_friends_dps.php
	public function getDPsByUserFriends($data) {
		//TODO: get the user friends and then their DPs. => only in 1 sql
		return $this->dpToUserCPRule->getDPsByUserFriends($data);
	}
	
	//$data = array("user_id" => 234234234);
	//user_id is from CP
	//to be used by entity/dp/get_user_dps.php
	public function getDPsByUser($data) {
		return $this->dpToUserCPRule->getDPsByUser($data);
	}
	
	//$data = array("dp_id" => 234234234);
	//dp_id is from CP
	//to be used by entity/dp/get_users_by_dp.php
	public function getDPUsers($data) {
		return $this->dpToUserCPRule->getDPUsers($data);
	}
	
	//get the manual dps the user is linked to in the CP
	//$data = array(network_id => 2, "network_user_id'" => 1232);
	//old logic, we were getting the dynamic dps first and then the manual dps
	public function getManualDpsByUserId ($data) {
		$hash = isset($data['hash_user_id']) ? $data['hash_user_id'] : IdService::getUserId($data['network_id'].$data['network_user_id']);
		$data = array("user_id"=> $hash);
		$dp_list = $this->dpToUserCPRule->getDPsByUser($data);

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
			return $this->dpToDpCPRule->getTribesByDP($data);
		}
		return false;
	}
	//get the manual dps and the affinity the user is linked to in the CP
	//$data = array(network_id => 2, "network_user_id'" => 1232);
	public function getManualDpsAndAffinityByUserId ($data) {
		$hash = isset($data['hash_user_id']) ? $data['hash_user_id'] : IdService::getUserId($data['network_id'].$data['network_user_id']);
		$data = array("user_id"=> $hash);
		$order_manual_dp_list = $this->dpToUserCPRule->getManualDpsAndAffinityByUserId($data);
		
		return $order_manual_dp_list;
	}
}
?>
