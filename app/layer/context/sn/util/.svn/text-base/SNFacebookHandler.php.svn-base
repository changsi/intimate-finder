<?php

class SNFacebookHandler {

	public function __construct() {
	}

	//rawId is "1234565_3514565" where 3514565 is the post id
	public function getPostIdFromString($rawId) {
		$pos = strpos($rawId,"_") + 1;//Search for '_'
		$postId = substr($rawId,$pos);//cut the string to return only 3514565
		return $postId;
	}

}


?>
