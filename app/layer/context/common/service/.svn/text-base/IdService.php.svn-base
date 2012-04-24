<?php
/*
get objectid in idservice receives a string a create a hash id

get book id(author+name)
get music id ()
...
in content 
*/
require_once getRuleFilePath("core_platform.UserCPRule");
require_once getRuleFilePath("core_platform.ObjectCPRule");

class IdService {
	
	public static function getUserId($user_hash) {
		return UserCPRule::getUserId($user_hash);
	}
	
	public static function getUrlId($user_hash) {
		return ObjectCPRule::getUrlId($user_hash);
	}
	
	public static function getObjectId($object_hash) {
		return UserCPRule::getUserId($object_hash);
	}
}
?>
