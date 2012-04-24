<?php
require getConfigFilePath("memcache_config");

class MemcacheHandler {
	
	private $meetupTopicIDsMemcache;
	
	public function __construct() {
		global $MEETUP_TOPIC_IDS;
			
	    $this->meetupTopicIDsMemcache = new Memcache();
	    $this->meetupTopicIDsMemcache->connect($MEETUP_TOPIC_IDS["HOST"], $MEETUP_TOPIC_IDS["PORT"]) or die ("Could not connect");
	}
	
	//
	public function setTopicID($data) {
		global $MEETUP_TOPIC_IDS;
		return $this->meetupTopicIDsMemcache->set($MEETUP_TOPIC_IDS["AFFIX"] . $data["key"], $data["value"]);
	}
	
	public function getTopicID($data) {
		global $MEETUP_TOPIC_IDS;
		return $this->meetupTopicIDsMemcache->get($MEETUP_TOPIC_IDS["AFFIX"]. $data["key"]);
	}
	
	public function deleteTopicID($data) {
		global $MEETUP_TOPIC_IDS;
		return $this->meetupTopicIDsMemcache->set($MEETUP_TOPIC_IDS["AFFIX"] . $data["key"], null);
	}
	
	
	
	
}
?>
