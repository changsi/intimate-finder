<?php

require "/var/www/dorothy_app/app/script/common.php";
require getContextFilePath("content.service.TopicService");
require getContextFilePath("common.util.MemcacheHandler");

$memcache_handler = new MemcacheHandler();

$cached_topic_id = $memcache_handler->getTopicID(array('key'=>'444'));

echo empty($cached_topic_id)?"true\n":"false\n";

?>