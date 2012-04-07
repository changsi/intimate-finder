<?php 

//http://www.meetup.com/cities/us/ny/new_york/?offset=24600&sort=default&psize=50&radius=1000.0&show=results

//http://www.meetup.com/cities/us/


	echo "start fetching "."\n";
	$url = "http://www.meetup.com/cities/us/";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$html = curl_exec($ch);
	
	$dom = new domDocument;
	
	/*** load the html into the object ***/
	$dom->loadHTML($html);
	
	/*** discard white space ***/
	$dom->preserveWhiteSpace = false;
	
	$n = 0;
	$states = array();
	$anchors = $dom->getElementsByTagName('a');
	$start = time();
	for($i =0; $i<$anchors->length;$i++){
		if(startsWith($anchors->item($i)->getAttribute('href'), "http://www.meetup.com/cities/us/")){
			//echo $anchors->item($i)->getAttribute('href')."\n";
			$states[$anchors->item($i)->getAttribute('href')] = $anchors->item($i)->getAttribute('href')."?all=1";
			$n++;
		}
	}
	
	echo "we get ".$n." states link.\n";
	
	$start_url = array();
	$n = 0;
	//$f = fopen("urls.txt", "w");
	foreach($states as $state=>$link){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$html = curl_exec($ch);
		
		$dom = new domDocument;
		
		$dom->loadHTML($html);
		
		$dom->preserveWhiteSpace = false;
		
		$anchors = $dom->getElementsByTagName('a');
		
		
		for($i =0; $i<$anchors->length;$i++){
			if(startsWith($anchors->item($i)->getAttribute('href'), $state)){
				//fwrite($f, $anchors->item($i)->getAttribute('href')."\n");
				$start_url[] = $anchors->item($i)->getAttribute('href');
				$n++;
			}
		}
		
	}
	//fclose($f);
	$end = time();
	echo "we get ".$n." city link. and it takes ".($start-$end)." seconds!\n";
	
	
	//http://www.meetup.com/cities/us/ny/brooklyn/?sort=default&psize=50&radius=100.0&show=results
	
	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	


?>
