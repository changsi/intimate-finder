<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

require getLibFilePath("sn.handler.SNHandler");

class TwitterHandler extends SNHandler {
	private $TwitterAdapter;
	private $user_id;
	
	public function __construct(TwitterAdapter $TwitterAdapter) {
		$this->TwitterAdapter = $TwitterAdapter;
		
		$this->user_id = $this->TwitterAdapter->getUserId();
	}
	
	public function logout() {
		$content = $this->TwitterAdapter->post("account/end_session");
		
		if (isset($content) && isset($content->error) && $content->error == "Logged out." && isset($content->request) && $content->request == "/1/account/end_session.json") {
			return true;
		}
		
		return false;
	}
	
	public function getFeeds($page = 1, $limit = 200) {
		$content = $this->TwitterAdapter->get("statuses/home_timeline", array("page" => $page, "count" => $limit, "trim_user" => true));

		$items = array();
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$stdObj = $content[$i];
				
				$id = $stdObj->id_str;
				$text = $stdObj->text;
				$urls = $this->getUrlsFromText($text);
				$date = $stdObj->created_at;
				
				if (count($urls) > 0) {
					$this->prepareText($text);
					$this->prepareDate($date);
					
					$items[] = array("user_id" => $this->user_id, "id" => $id, /*"text" => $text,*/ "urls" => $urls, "date" => $date);
				}
			}
		}
		return $items;
	}
	
	//get login user"s followers
	public function getFollowersIds() {
		$content = $this->TwitterAdapter->get("followers/ids");
		
		$items = array();
		
		//Twitter data format changed. 2011-11-01
		$content = $content->ids;
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$items[] = array("user_id" => $this->user_id, "follower_id" => $content[$i]);
			}
		}
		return $items;
	}
	
	//get login user"s friends
	public function getFriendsIds() {
		$content = $this->TwitterAdapter->get("friends/ids");
		
		$items = array();
		
		//Twitter data format changed. 2011-11-01
		$content = $content->ids;
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$items[] = array("user_id" => $this->user_id, "friend_id" => $content[$i]);
			}
		}
		return $items;
	}
	
	//get friends id list by user id
	public function getFriendsIdsByUserId($user_id) {
		$content = $this->TwitterAdapter->get("friends/ids", array("user_id" => $user_id));
		
		$items = array();
		
		$content = $content->ids;
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$items[] = $content[$i];
			}
		}
		
		return $items;
	}
	
	//get users' names by users ids (maximum 100, comma separated)
	public function getUserNamesByUserIds($user_ids) {
		$content = $this->TwitterAdapter->get("users/lookup", array("user_id" => $user_ids));
		
		$names = array();
		
		if (isset($content) && !isset($content->error)) {
			foreach($content as $item) {
				$names[$item->id] = $item->screen_name;
			}
		}
		return $names;
	}
	
	//Gets Feeds from user id
	public function getFeedsByUserId($user_id, $page = 1, $count = 200, $since_id = 0) {
		$content = $this->TwitterAdapter->get("statuses/user_timeline", array("user_id" => $user_id, "page" => $page, "count" => $count, "since_id" => $since_id, "trim_user" => true));
		
		$items = array();
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$stdObj = $content[$i];
				
				$id = $stdObj->id_str;
				$text = $stdObj->text;
				$urls = $this->getUrlsFromText($text);
				$date = $stdObj->created_at;
				
				$this->prepareText($text);
				$this->prepareDate($date);
					
				$items[] = array("user_id" => $user_id, "id" => $id, "text" => $text, "urls" => $urls, "date" => $date);
			}
		}
		
		return $items;
	}
	
	//TODO
	//get user id list from most recent wall tweets
	public function getWallTweetsUserIds($user_id, $page = 1, $tweets_limit = 500, $users_limit = 100){
		$content = $this->TwitterAdapter->get("statuses/home_timeline", array("user_id" => $user_id, "page" => $page, "count" => $tweets_limit, "trim_user" => true));
		
		$ids = array();
		$count = 0;
		
		if (isset($content) && !isset($content->error)) {
			for ($i = 0; $i < count($content); ++$i) {
				$stdObj = $content[$i];
				
				$tweet_user_id = $stdObj->user->id;
				
				if(!isset($ids[$tweet_user_id])) {
					$count++;
				}
				
				$ids[$tweet_user_id] = 1;
				
				if($count >= $users_limit) {
					break;
				}
			}
		}
		
		$new_ids = array();
		
		foreach($ids as $id=>$value) {
			$new_ids[] = $id;
		}
		
		return $new_ids;
		//return array(123123,34234234,1231231,23423423);
	}

}
?>
