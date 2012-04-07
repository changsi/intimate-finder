<?php
require_once getRuleFilePath("Rule");

class NetworkPostUrlFacebookAppRule extends Rule {
	
	public function insertUserNetworkPostUrl($data) {
		$created_date = $this->getCurrentTimestamp();	//get current timestamp
		$url = $this->addSlashes($data["url"]);
		$sql = "insert ignore into network_post_url (network_id, network_user_id, network_post_id, url_id, url, created_date) values (" . $data["network_id"] . ", " . $data["network_user_id"] . ", " . $data["network_post_id"] . ", " . $data["url_id"] . ", '" . $url . "', '$created_date')";
		
		return $this->setData($sql);
	}
	
	
	//get unprocessed url count
	public function getUserUrlsCountForCP() {
		$sql = "SELECT count(*) as count FROM network_post_url WHERE control_flag = 0;";
		
		return $this->getData($sql);
	}
	
	//get unprocessed url data for core-platform script
	//$data = array("start" => 0, "limit" => 100);
	public function getUrlsForCP($data) {
		$sql = "SELECT npu.network_id,npu.network_user_id, npu.network_post_id, npu.url, npu.real_url, npu.created_date, npu.url_id FROM network_post_url npu WHERE control_flag = 0 ORDER BY npu.created_date ASC LIMIT " . $data["start"] . ", " . $data["limit"] . ";";
	
		return $this->getData($sql);
	}
	
	//update control flags
	//$data = array("network_id" => 1, "control_flag" => 1, "network_post_id" => "1", "url_id" => "11112")
	public function updateUserNetworkPostUrlControlFlag($data) {	
		$sql = "UPDATE network_post_url SET control_flag = " . $data["control_flag"] . " WHERE network_id = " . $data["network_id"] . " AND network_post_id = " . $data["network_post_id"] . " AND url_id = " . $data["url_id"];
	
		return $this->setData($sql);
	}
	
	public function getPostsOfUsersWithCount($data) {
			$network_user_ids = $data["network_user_ids"];
			
			$limit = $data["limit"];
			$current_time = $this->getCurrentTimestamp();        //get current timestamp
			
			$sql = " SELECT n.network_user_id, n.url_id as object_id, y.total,TIMEDIFF('".$current_time."',n.created_date) as DELTA_Hour,DateDIFF('".$current_time."',n.created_date) as DELTA_Day". 
					" FROM".
						" network_post_url n, ".
						" (select np.url_id, count(np.url_id) as total from network_post_url np where np.network_user_id in(".$network_user_ids.") group by np.url_id)y". 
					" WHERE" . 
						" y.url_id = n.url_id and".
						" n.network_user_id in (".$network_user_ids. ") ".
						" group by n.network_user_id, n.url_id ". 
						" order by n.network_user_id";
			;
			
//			echo $sql;
			return $this->getData($sql);
    }
    
    public function getUrlByIDs($data) {
    	$object_ids = $data["object_ids"];
    	$sql = "select distinct url_id as object_id, url as name, url from network_post_url where url_id in( ".$object_ids." )";
    	return $this->getData($sql);
    }
    	

}
?>
