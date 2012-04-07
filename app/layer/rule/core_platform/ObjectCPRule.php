<?php
require_once getRuleFilePath("Rule");
require_once getLibFilePath("web.normalizer.URLNormalizer");

class ObjectCPRule extends Rule {
	
	public static function getUrlId($url) {
		$url = self::prepareUrl($url);
		return self::getTextCode($url);
	}
	
	private static function startsWith($haystack, $needle)
	{
   		$length = strlen($needle);
    		return (substr($haystack, 0, $length) === $needle);
}	

	public static function prepareUrl($url) {
		if (isset($url) && $url) {
			if(!self::startsWith($url, 'http://') && !self::startsWith($url, 'https://')){
				if(self::startsWith($url, 'http:/')){
					$url = 'http://'.substr($url, 6);
				}
				if(self::startsWith($url, 'https:/')){
					$url = 'https://'.substr($url, 7);
				}
			}
			$uRLNormalizer = new URLNormalizer();
			$uRLNormalizer->setUrl($url);
			$url = $uRLNormalizer->normalize();
			
			$url = StringHelper::normalizeString($url);
			
			if ($url) {
				$main_url = $url;
				$queryString = "";
				
				$index = strpos($url, "?");
				
				if ($index > 0) {
					$main_url = substr($url, 0, $index);
					$queryString = $index + 1 < strlen($url) ? substr($url, $index + 1) : "";
					$queryString = trim($queryString);
				}
				
				$index = strpos($main_url, "://");
				$index = $index > 10 ? 0 : $index;
				
				while (strrpos($main_url, "//") > $index + 2) {
					$main_url = str_replace($main_url, "//", "/");
				}
				
				if (strlen($main_url) > 0) {
					if (substr($main_url, strlen($main_url) - 1) == '/' && strpos($main_url, '?') === FALSE) {
						$main_url = substr($main_url, 0, strlen($main_url) - 1);
					}
					
					//adding protocol in case doesn't exist.
					$main_url = trim($main_url);
					if (strpos($main_url, "http:") === false && strpos($main_url, "https:") === false) {
						$main_url = "http://" . $main_url;
					}
					
					$url = $main_url . (!$queryString ? "" : "?".$queryString);
				}
				else {
					$url = "";
				}
			}
		}

		return $url;
	}
}
?>
