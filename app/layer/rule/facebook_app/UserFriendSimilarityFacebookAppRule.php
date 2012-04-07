<?php
require_once getRuleFilePath("Rule");
require_once getLibFilePath("util.StringHelper");


class UserFriendSimilarityFacebookAppRule extends Rule {
	public function insertUserFriendSimilarity($data) {
		$created_date = $this->getCurrentTimestamp();

		$sql = "insert into user_friend_similarity (network_user_id_from, network_user_id_to, similarity) 
		values(".$data["network_user_id_from"].", '".$data["network_user_id_to"]."', ".$data['similarity'].") 
		on duplicate key update similarity=values(similarity)";

		return $this->setData($sql);
	}

	public function getTopFriends($data){

		$sql = "select network_user_id_to from user_friend_similarity where network_user_id_from =".$data['network_user_id']." order by similarity desc";
		return $this->getData($sql);
	}
}

?>