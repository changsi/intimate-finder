<?php 

$n = 876;
$url = "http://stackoverflow.com/tags?page=1&tab=popular";
for($i=1; $i<=$n; $i++){
	echo "start fetching ".$i."\n";
	$url = "http://stackoverflow.com/tags?page=".$i."&tab=popular";
	
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
	
	$table = $dom->getElementById('tags-browser');
	$rows = $table->getElementsByTagName('tr');
	$string = "";
	foreach ($rows as $row)
	{
		/*** get each column by tag name ***/
		$cols = $row->getElementsByTagName('td');
		/*** echo the values ***/
		$string= $string.trim($cols->item(0)->getElementsByTagName('a')->item(0)->nodeValue)."\n";
		$string = $string.trim($cols->item(0)->getElementsByTagName('span')->item(2)->nodeValue)."\n";
		if($cols->item(0)->getElementsByTagName('div')->length>3){
			$string= $string.trim($cols->item(0)->getElementsByTagName('div')->item(0)->nodeValue)."\n";
		}else{
			$string = $string."\n";
		}
		$string= $string.trim($cols->item(1)->getElementsByTagName('a')->item(0)->nodeValue)."\n";
		$string= $string.trim($cols->item(1)->getElementsByTagName('span')->item(2)->nodeValue)."\n";
		if($cols->item(1)->getElementsByTagName('div')->length>3){
			$string= $string.trim($cols->item(1)->getElementsByTagName('div')->item(0)->nodeValue)."\n";
		}else{
			$string = $string."\n";
		}		
		$string= $string.trim($cols->item(2)->getElementsByTagName('a')->item(0)->nodeValue)."\n";
		$string= $string.trim($cols->item(2)->getElementsByTagName('span')->item(2)->nodeValue)."\n";
		if($cols->item(2)->getElementsByTagName('div')->length>3){
			$string= $string.trim($cols->item(2)->getElementsByTagName('div')->item(0)->nodeValue)."\n";
		}else{
			$string = $string."\n";
		}		
		$string= $string.trim($cols->item(3)->getElementsByTagName('a')->item(0)->nodeValue)."\n";
		$string= $string.trim($cols->item(3)->getElementsByTagName('span')->item(2)->nodeValue)."\n";
		if($cols->item(3)->getElementsByTagName('div')->length>3){
			$string= $string.trim($cols->item(3)->getElementsByTagName('div')->item(0)->nodeValue)."\n";
		}else{
			$string = $string."\n";
		}	
	}
	
	$f = fopen("../tmp_files/".$i.".txt", "w");
	
	fwrite($f, $string);
	fclose($f);
	curl_close($ch);
	echo "finish fetching ".$i."\n";
}



?>