<?php

require '/var/www/dorothy_app/app/lib/web/simple_html_dom/simple_html_dom.php';
require "/var/www/dorothy_app/app/script/common.php";
require getContextFilePath("content.service.TopicService");
require getContextFilePath("common.util.MemcacheHandler");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

$topicService = new TopicService();
$topicService->setDBDriverForLiveSystem($live_DB_driver);

$memcache_handler = new MemcacheHandler();

$start = 0;
if(isset($argv[1])){
	$start = $argv[1];
}

$limit = 29756;
if(isset($argv[2])){
	$limit = $argv[2];
}

$f = fopen ("urls.txt", "r");
$ln= 0;
while ($line= fgets ($f)) {
	++$ln;
	echo "************url ".$ln." is getting processed!******************\n";
	
	if ($line===FALSE) print ("FALSE\n");
	
	if($ln>$start && $ln <$limit){
		fetchOneLocation(trim($line));
	}
	
}
fclose ($f);


function fetchOneLocation($url){
	global $topicService;
	global $memcache_handler;
	$url = $url."?psize=50&sort=member_count&radius=25.0&show=results";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$html = curl_exec($ch);
	
	$dom = str_get_html($html);
	$node=$dom->find('p.D_searchResultsCount');
	$count = explode(' ', $node[0]->plaintext);
	$total = str_replace(',','',$count[0]);
	echo "total pages for this url is $total !\n";
	
	$counter = 0;
	echo "###########################    page 1   #########\n";
	$topic_lists = $dom->find('div.D_topicList');
	echo count($topic_lists)."\n";
	foreach ($topic_lists as $topic_list){
		$topics = $topic_list->find('a');
		foreach ($topics as $topic){
			$topic_id = trim(get_id_from_string($topic->class));
			$topic = trim(html_entity_decode($topic->plaintext,ENT_QUOTES ));
			$cached_topic_id = $memcache_handler->getTopicID(array('key'=>$topic_id));
			if(empty($cached_topic_id)){
				
				
				$data = array("topic_id"=>$topic_id, "topic"=>$topic);
				$topicService->setUserTopic($data);
				echo "insert a new topic $topic_id $topic.\n";
				$memcache_handler->setTopicID(array('key'=>$topic_id, 'value'=>$topic_id));
			}
			
		}
		$counter++;
	}
	$dom->clear();
	unset($dom);
	
	$page = 1;
	
	for(;$counter<$total;){
		echo "###########################    page ".($page+1)."   #########\n";
		$url = $url."?psize=50&sort=member_count&radius=25.0&show=results"."&offset=".(50*$page);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
		$html = curl_exec($ch);
		$dom = new simple_html_dom();
	
		// Load HTML from a string
		$dom->load($html);
		$topic_lists = $dom->find('div.D_topicList');
		echo count($topic_lists)."\n";
		foreach ($topic_lists as $topic_list){
			$topics = $topic_list->find('a');
			foreach ($topics as $topic){
				$topic_id = trim(get_id_from_string($topic->class));
				$topic = trim(html_entity_decode($topic->plaintext,ENT_QUOTES ));
				$cached_topic_id = $memcache_handler->getTopicID(array('key'=>$topic_id));
				if(empty($cached_topic_id)){
					
					$data = array("topic_id"=>$topic_id, "topic"=>$topic);
					$topicService->setUserTopic($data);
					echo "insert a new topic $topic_id $topic.\n";
					$memcache_handler->setTopicID(array('key'=>$topic_id, 'value'=>$topic_id));
				}
			}
			$counter++;
		}
		$page++;
		$dom->clear();
		unset($dom);
	}
}




function get_id_from_string($text){
	
	// number selection
	$selection = "0123456789";
	
	$arr = str_split($text);
	$len = count($arr);
	$count = -1;
	
	$output = "";
	
	while (++$count < $len) {
		$selected = $arr[$count];
		if (strpos($selection, $selected) !== false)
			$output .= $selected;
	}
	return $output;
}


?>