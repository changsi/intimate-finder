<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

date_default_timezone_set("America/New_York");

if (isset($_SERVER["HTTP_HOST"])) {
	//define("HOST_PREFIX", "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]));
	define("HOST_PREFIX", "http://" . $_SERVER["HTTP_HOST"] . "/dorothy_app/app");
	define("DEFAULT_IMAGES_PATH", $_SERVER["HTTP_HOST"] . "/dorothy_app/data/default_images/");
	
	define("CSS_PATH", HOST_PREFIX . "/layer/presentation/webroot/css/");
	define("JS_PATH", HOST_PREFIX . "/layer/presentation/webroot/js/");
}

define("LOCAL_HOST", "http://127.0.0.1/dorothy_app/app");
define("TEMP_PATH", "/tmp/");
define("APPID",'209332149157812');
define("USERLIMIT", 100);

define("MAX_OVERFLOW_VALUE", "2147483647");
define("MIN_OVERFLOW_VALUE", "-2147483648");

define("NUMBER_OF_FB_FEEDS_TO_FETCH_LIMIT", 1);
define("NUMBER_OF_FB_FRIENDS_TO_FETCH_LIMIT", 10);
define("NUMBER_OF_FB_FRIENDS_FEEDS_TO_FETCH_LIMIT", 1);
define("PERMISSION",
	'user_about_me,		
	user_activities,		
	user_birthday,
	user_checkins,		
	user_education_history,		
	user_events,	
	user_groups,		
	user_hometown,
	user_interests,
	user_likes,		
	user_location,	
	user_notes,		
	user_photo_video_tags,	
	user_photos,		
	user_questions,
	user_relationship_details,	
	user_relationships,	
	user_religion_politics,
	user_status,	
	user_videos,
	user_website,		
	user_work_history, 
	email,
	friends_about_me,		
	friends_activities,		
	friends_birthday,
	friends_checkins,		
	friends_education_history,		
	friends_events,
	friends_groups,		
	friends_hometown,
	friends_interests,		
	friends_likes,		
	friends_location,
	friends_notes,
	friends_photo_video_tags,		
	friends_photos,	
	friends_questions,
	friends_relationship_details,	
	friends_relationships,	
	friends_religion_politics,
	friends_status,
	friends_videos,
	friends_website,		
	friends_work_history,
	read_friendlists,
	read_insights,
	read_mailbox,
	read_requests,		
	read_stream,
	xmpp_login,
	ads_management,
	create_event,	
	manage_friendlists,
	manage_notifications,
	offline_access,
	user_online_presence,
	friends_online_presence,
	publish_checkins,
	publish_stream,
	rsvp_event,
	publish_actions');

//TODO in the future the object_types array will be in a script that will be ran once when the system starts up. Additionally we can put this array in memcache	
//To use this array, juste unserialize it.
define ("OBJECT_TYPES", 
			serialize (
				array (
					"unknown" => "0",
					"movie" => "1",
					"book" => "2",
					"music" => "3",
					"game" => "4",
					"television" => "5",
					"activity" => "6",
					"interest" => "7",
					"other" => "8"
					)
			)
);

//In the future this has to be loaded in memory and read from the config file.
define ("PROFILE_CATEGORIES", 
			serialize (
				array (
					"9995999" => "male",
					"9996999" => "female",
					"9997999" => "kid",
					"9998999" => "teenager",
					"9999999" => "adult",
					"99910999" => "experienced",
					"99911999" => "old",
					"99912999" => "high_school",
					"99913999" => "college",
					"99914999" => "grad_school",
					"99915999" => "single",
					"99916999" => "in_a_relationship",
					"99917999" => "engaged",
					"99918999" => "married",
					"99919999" => "its_complicated",
					"99920999" => "open",
					"99921999" => "widowed",
					"99922999" => "separated",
					"99923999" => "divorced",
					"99924999" => "civil_union",
					"99925999" => "domestic_partnership",
					)
			)
);

define ("ALCHEMY_CATEGORIES", 
			serialize (
				array (
					"52909" => "arts_entertainment",
					"53818" => "business",
					"28813" => "computer_internet",
					"48374" => "culture_politics",
					"30399" => "gaming",
					"58954" => "health",
					"47792" => "law_crime",
					"48378" => "religion",
					"52868" => "recreation",
					"49049" => "science_technology",
					"5045" => "sports",
					"44675" => "weather"
					)
			)
);

define ("QUESTIONS", 
	serialize (
		array (
			0 => array (
			     'question' =>"Age?",
			     'answers' => array (
		               0 => array("answer" => "kid"),
		               1 => array("answer" => "teenager")
				)
			),
			1 => array(
			     'question' =>"Gender?",
			     'answers' => array (
			          0 => array("answer" => "male") ,
			          1 => array("answer" => "female")
				)
			),
			2 => array(
			     'question' =>"Relation ship?",
			     'answers' => array (
			          0 => array("answer" => "couple") ,
			          1 => array("answer" => "married")
				)
			)
		)
	)
);

define ("FIRST_QUESTIONS", 
	serialize (
		array (0,1)
	)
);

define ("DEPENDENCIES_QUESTIONS", 
	serialize (
		array (
			"0.1,1.1" => array (2),
			"2.1" => "end"
		)
	)
);

define ("QUESTIONS_TO_TRIBES", 
	serialize (
		array (
			"0.1,1.1,2.1" => array(12,654,645),
			"0.1,1.1,2.0" => array(45,654,847)
		)
	)
);

define ("MIN_AFFINITY_TRIBE_DP", 0.8);//deprecated

define ("MIN_AFFINITY_DP_USER", 0.6);

define ("MIN_USER_TO_USER_AFFINITY", 0.5);

define ("FREQUENCY_TO_UPDATE_FACEBOOK_DATA", 432000); //this number is based on seconds, currently it is 432000 means 5 days

define ("EXPIRED_USER_BUCKETS_SIZE", 300); // this number define the buckets size of expired users (for update_facebook_user_data.php)

session_save_path(TEMP_PATH);//where fb stores the session
?>
