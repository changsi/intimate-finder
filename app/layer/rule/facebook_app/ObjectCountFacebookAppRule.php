<?php
require_once getRuleFilePath("Rule");

class ObjectCountFacebookAppRule extends Rule {
	
	public function updateObjectCount() {
		$sql = "insert into object_count(object_type_id,object_id,count) select object_type_id, object_id, count(*) as count from user_object group by object_type_id,object_id on duplicate key update count=values(count)";
		
		return $this->setData($sql);
	}

	public function getTopContentsFromSimilarFriends($data) {
		$str = $this->getCSVStringFromArray($data["friends_ids"]);
		
		/*if(!empty($str)){
			$sql = "select X.object_type_id, X.object_id ,sum(X.count) as count from (select uo.network_user_id,uo.object_type_id,uo.object_id,oc.count from user_object uo inner join object_count oc on uo.object_type_id=oc.object_type_id AND uo.object_id=oc.object_id where network_user_id IN(" . $str . ") )X group by X.object_id, X.object_type_id order by X.object_type_id,count desc";

			return $this->getData($sql);
		}*/
		
		
		if(!empty($str)){
			$sql = "select X.object_type_id, X.object_id ,sum(X.count) as count from (select uo.network_user_id,uo.object_type_id,uo.object_id,oc.count from user_object uo inner join object_count oc on uo.object_type_id=oc.object_type_id AND uo.object_id=oc.object_id where uo.object_type_id =".$data['object_type_id']." and oc.object_id NOT IN (select object_id from user_object where object_type_id =".$data['object_type_id']." and  network_user_id=".$data['network_user_id'].") and network_user_id IN(" . $str . ") )X group by X.object_id order by count desc ".$data['extra'];
			$sql = "select X.object_type_id, X.object_id ,
							sum(X.count) as count from 
							(SELECT uo.network_user_id,uo.object_type_id,uo.object_id,oc.count FROM user_object uo 
							inner join object_count oc on uo.object_type_id=oc.object_type_id AND uo.object_id=oc.object_id 
							WHERE uo.object_type_id =".$data['object_type_id']." and oc.object_id NOT IN 
								(SELECT object_id FROM user_object WHERE object_type_id =".$data['object_type_id']." AND  network_user_id=".$data['network_user_id'].") AND network_user_id IN(" . $str . ") )X 
							GROUP BY X.object_id 
							order by count desc ".$data['extra'];
			echo $sql;
			return $this->getData($sql);
		}
		
		
		return array();
	}
}
?>
