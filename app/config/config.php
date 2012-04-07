<?php


date_default_timezone_set("America/New_York");

if (isset($_SERVER["HTTP_HOST"])) {
	define("HOST_PREFIX", "http://" . $_SERVER["HTTP_HOST"] . "/facebook_checkin/app");
}

define("LOCAL_HOST", "http://127.0.0.1/facebook_checkin/app");
define("APPID",'');

define("PERMISSION",
	'user_about_me,		
	user_birthday,
	user_checkins,		
	user_location,	
	user_photo_video_tags,	
	user_photos,		
	email,
	friends_about_me,		
	friends_birthday,
	friends_checkins,			
	friends_location,
	friends_photos,	
	read_friendlists,	
	read_stream,
	publish_actions');


session_save_path(TEMP_PATH);//where fb stores the session
?>
