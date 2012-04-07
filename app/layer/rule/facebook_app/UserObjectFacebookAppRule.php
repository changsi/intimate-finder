<?php
require_once getRuleFilePath("Rule");

class UserObjectFacebookAppRule extends Rule {
	
	public function insertUserObject($data) {
		$sql = "insert ignore into user_object (network_user_id, object_id, create_date) values(".$data["network_user_id"].", ".$data["object_id"].", '".$data["created_time"]."')";

		return $this->setData($sql);
	}
	
	public function getObjectsOfUsersWithCount($data) {
			$network_user_ids = $data["network_user_ids"];
			$object_type_id = $data["object_type_id"];
			$limit = $data["limit"];
			$current_time = $this->getCurrentTimestamp();        //get current timestamp
			
			$sql = "SELECT u.network_user_id, u.object_id, y.total, TIMEDIFF('".$current_time."',u.date) as DELTA_Hour,DateDIFF('".$current_time."',u.date) as DELTA_Day FROM ".
                    "user_object u, ".
                    "(select  uo.object_id, count(uo.object_id) as total from user_object uo where uo.object_type_id = ".$object_type_id." and uo.network_user_id in(".$network_user_ids.") group by object_id) y".
               " WHERE u.network_user_id in (".$network_user_ids. ") AND object_type_id = ".$object_type_id." AND y.object_id = u.object_id limit ".$limit;
			
//			$sql = "SELECT un.name, u.network_user_id, u.object_id, y.total, TIMEDIFF('".$current_time."',u.date) as DELTA_Hour,DateDIFF('".$current_time."',u.date) as DELTA_Day FROM ".
//                         "user_object u, user_network un ".
//                         "(select un.name, uo.network_user_id, uo.object_id, count(uo.object_id) as total from user_network un, user_object uo where un.network_user_id = uo.network_user_id and uo.object_type_id = ".$object_type_id." and uo.network_user_id in(".$network_user_ids.") group by object_id) y".
//               " WHERE u.network_user_id in (".$network_user_ids. ") AND object_type_id = ".$object_type_id." AND y.object_id = u.object_id";
			
//			echo $sql;
			return $this->getData($sql);
    	}
	
	 
}
?>
