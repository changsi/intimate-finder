<?php

require "/var/www/dorothy_app/app/script/common.php";
require getContextFilePath("content.service.TopicService");
require getContextFilePath("common.util.MemcacheHandler");

$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], "", false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

$topicService = new TopicService();
$topicService->setDBDriverForLiveSystem($live_DB_driver);


$topics = $topicService->getAllTopics();

$memcache_handler = new MemcacheHandler();
foreach($topics as $topic){
	$topic_id = $topic['topic_id'];
	$data = array('key'=>$topic_id, 'value'=>$topic_id);
	$memcache_handler->setTopicID($data);
}


?>