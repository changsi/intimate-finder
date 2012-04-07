<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

abstract class SNHandler {
	public abstract function getFeeds($page = 1, $limit = 200);
	//public abstract function getFeeds_first($page = 1, $limit = 200);
	public abstract function getFollowersIds();
	public abstract function getFriendsIds();
	public abstract function getFeedsByUserId($user_id, $page = 1, $limit = 200);
	//public abstract function getFeedsFromUserId_first($user_id, $page = 1, $limit = 200);
	
	public static function getUrlsFromText($text) {
		$urls = array();
		
		$text = trim($text);
		
		if ($text) {
			$protocols = "http|https|ftp|file";
			
			/*	
			//ORIGINAL
			$regex = "((" . $protocols . ")\:\/\/)?"; // SCHEME: if "?" is at the end of the regex, it means that the protocol is not mandatory. To make the protocol mandatory, remove the "?" at the end of the regex.
			$regex .= "([a-z0-9+!*(),;?&=\$_\.-]+(\:[a-z0-9+!*(),;?&=\$_\.-]+)?@)?"; // User and Pass
			$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
			$regex .= "(\:[0-9]{2,5})?"; // Port
			$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
			$regex .= "(\?([a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)*)?"; // GET Query
			$regex .= "(#[a-z0-9;:@&%=+\/\$_.-]*)?"; // Anchor
			$regex = "/" . $regex . "/i"; 
			*/
			//JP CHANGES
			$regex = "((" . $protocols . ")\:\/\/)?"; // SCHEME: if "?" is at the end of the regex, it means that the protocol is not mandatory. To make the protocol mandatory, remove the "?" at the end of the regex.
			$regex .= "([a-z0-9+!*(),;?&=\$_\.-]+(\:[a-z0-9+!*(),;?&=\$_\.-]+)?@)?"; // User and Pass
			$regex .= "([a-z0-9-\._]*)\.([a-z0-9-_]+)"; // Host or IP
			$regex .= "(\:[0-9]{2,5})?"; // Port
			$regex .= "(\/[^?#]*)*\/?"; // Path
			$regex .= "(\?([&]*[^&=]*[=]*[^&=]*)*)?"; // GET Query
			$regex .= "(#.*)?"; // Anchor
			$regex = "/" . $regex . "/i";
			
			//another version of the pattern
			$regex = "/(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?#Username:Password)(?:\w+:\w+@)?(?#Subdomains)(?:(?:[-\w]+\.)+(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|edu|[a-z]{2}))(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?#Query)(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?#Anchor)(?:#(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)?/i";
			
			preg_match_all($regex, $text, $matches);
			$urls = isset($matches[0]) ? $matches[0] : array();
			
			for ($i = 0; $i < count($urls); ++$i) {
				if (substr($urls[$i], strlen($urls[$i]) - 1) == ".") {
					$urls[$i] = substr($urls[$i], 0, strlen($urls[$i]) - 1);
				}
				
				if (substr($urls[$i], 0, 1) == ".") {
					unset($urls[$i]);
					--$i;
				}
			}
		}
		return $urls;
	}
	
	protected function prepareText(&$text) {
		$text = str_replace("\r", "", $text);
		$text = str_replace("\n", "<br/>", $text);
	}
	
	protected function prepareDate(&$date) {
		$time = strtotime($date);
		$date = date("Y-m-d H:i:s", $time);
	}
	
	//rawId is "1234565_3514565" where 3514565 is the post id
	public static function getPostIdFromString($rawId) {
		$pos = strpos($rawId,"_") + 1;//Search for '_'
		$postId = substr($rawId,$pos);//cut the string to return only 3514565
		return $postId;
	}
	
	public static function getRelationShipIdFromString($relationShip){
		$relationship = StringHelper::normalizeString($relationShip);
		if (!empty($relationShip)) {
				switch ($relationship) {
				
					case 'single':
						return 1;
						break;
					
					case 'couple':
						return 2;
						break;
				
					case 'engaged':
						return 3;
						break;
					
					case 'married':
						return 4;
						break;
						
					case 'complicated':
						return 5;
						break;
					
					case 'free':
						return 6;
						break;
						
					case 'widow':
						return 7;
						break;
					
					case 'separated':
						return 8;
						break;
						
					case 'divorced':
						return 8;
						break;
					
					case 'civil_union':
						return 10;
						break;
						
					case 'domestic_partnership':
						return 11;
						break;
					default:
						return 0; //unknown
			}
		}
		return 0;
	}

	public static function getGenderIdFromString($gender) {
		$gender = trim(strtolower($gender));
		if (!empty($gender)) {
			switch ($gender) {
				case 'male':
					return 1;
				break;
				case 'female':
					return 2;
				break;
			}
		}
	}

	public static function getAgeFromBirthday($birthDate) {
		return floor((time() - strtotime($birthDate)) / (60*60*24*365));
	}
}

/*$matches = array();
$url = "as dsa das d http://komunitasweb.com/asdsa/d/asd?asd=as&as=s#s-ad=ss asdsad as dsad https://xxx.ssd.fg.google.com asd ftp://sss asjsdjk sd http://asd.sd.ccd?as=sa 3333 ftp://asd.sd.ccd/?as=sa ejkr jerh rejk https://as.as.com#ss=ss https://as.as.com?#ss=ss https://as.as.com/#ss=s/s https://as.as.com/#";

$regex = "((https?|ftp)\:\/\/)?"; // SCHEME
$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
$regex .= "(\:[0-9]{2,5})?"; // Port
$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
$regex .= "(\?([a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)*)?"; // GET Query
$regex .= "(#[a-z0-9;:@&%=+\/\$_.-]*)?"; // Anchor
$regex = "/" . $regex . "/"; 

if (preg_match_all($regex, $url, $matches)) {
	echo "Your url is ok.";
} else {
	echo "Wrong url.";
}

echo "<br><br>$url<br>";
echo "<pre>";print_r($matches);echo "</pre>";
die();*/
?>
