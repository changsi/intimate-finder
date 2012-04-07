<?php

require "/var/www/dorothy_app/app/script/common.php";
require getContextFilePath("content.service.TopicService");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

$topicService = new TopicService();
$topicService->setDBDriverForLiveSystem($live_DB_driver);

$f=fopen("topics.txt", "r");
while ($line= fgets ($f)) {
	
	if ($line===FALSE) print ("FALSE\n");
	$topic_id = trim($line);
	$topic = trim(fgets($f));
	$data = array("topic_id"=>$topic_id, "topic"=>$topic);
	$topicService->setUserTopic($data);
}
fclose ($f);

?>