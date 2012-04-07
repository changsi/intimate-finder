<?php
require_once getRuleFilePath("Rule");

class FriendCPRule extends Rule {
	
	public function getUserNetworkFriendsAffinity($data) {
		$sql = "select X.user_id as friend_id, affinity  from (select user_id_to as user_id, affinity from user_user where user_id_from=".$data["hash_user_id"]." union select user_id_from, affinity as user_id from user_user where user_id_to=".$data["hash_user_id"].") X inner join friend f on X.user_id = f.user_id_to where f.user_id_from=".$data["hash_user_id"]." order by affinity desc ".$data['extra'];
		//echo "$sql \n";
		return $this->getData($sql);
	}
	
	
}
?>
