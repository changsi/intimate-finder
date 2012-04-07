<?php
/* IMPORTANT THE /tmp/ (TEMP_PATH) folder must have the 777 perm
Receives an USER ID (from args) and get the access token from the DB (Client DB).
Based in the FB access_token, gets the correspondent user\'s profile, friends, and feeds. Then for each friend get the profile and feeds.
Then for each feeds, parse them and get the correspondent urls.
Save the urls to the table: network_post_url (FB DB)
Save the friends to the table: network_friend (FB DB)
Save the profile content (like books, music, movies, web-pages, etc...) to the table: user_object (FB DB)
Save the other profile variables like: age, gender, education, etc... to an output file.

Uses: SNFacebookService->getUserFeeds(...), SNFacebookService->getUserFriends(...), SNFacebookService->getUserProfile(...) and SNFacebookService->getUserFeedsUrls(...), UserService->insertUserNetworkProfile(...)
*/
require dirname(__FILE__) . "/common.php";

require getContextFilePath("user.service.UserService");
require getContextFilePath("sn.service.SNFacebookService_batch_test");
require getContextFilePath("content.service.ContentService");


//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//initialize service
$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);
$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);
$ContentService = new ContentService();
$ContentService->setDBDriverForLiveSystem($live_DB_driver);
ob_start();
echo "Print started: \n";
if ($argc == 0 || !isset($argv[1]) || !is_numeric($argv[1])) {
	die("USER_ID UNDEFINED!");
}

$user_id = $argv[1];
echo 'Network User id (fromargs) :'.$user_id." \n ";	

$data = array(
	"network_user_id" => $user_id,
	"network_id" => 2
);
$token = $UserService->getUserTokenFromLiveSystem($data);

if (isset($token[0]['access_token'])) {
	$access_token = $token[0]['access_token'];
	echo 'User token (fromDB) :'.$access_token." \n "; 
	$SNFacebookService->setAccessToken($token[0]);//set the access token from DB to FB API to access user info
	
	
	//User personal info from facebook and insert it into user_network_data table
	$user_data = array(
		"network_user_id" => $user_id,
		"limit" => NUMBER_OF_FB_FEEDS_TO_FETCH_LIMIT
	);
	
	$SNFacebookService->getEverything($user_data);
	
	/*
	$user_profile = $SNFacebookService->getUserProfile($user_data);
	echo "Inserting User profile data into user_network_data table \n";
	$recent_education_arr = isset($user_profile['education']) ? array_slice($user_profile['education'],-1,1) : '';
	$user_profile_data = array(
		"network_id" => 2,
		"network_user_id" => $user_id,
		"age" => isset($user_profile['birthday']) ? SNHandler::getAgeFromBirthday($user_profile['birthday']) : '0',
		"gender" => isset($user_profile['gender']) ? SNHandler::getGenderIdFromString($user_profile['gender']) : '0',
		"recent_education" => isset($recent_education_arr[0]['type']) ? $recent_education_arr[0]['type'] : '',
		"highest_education" => isset($recent_education_arr[0]['type']) ? $recent_education_arr[0]['type'] : '',//TODO code the logic for getting from facebook the highest education pursued by the user or find by ourselves
		"from_location" => isset($user_profile['hometown']) ? $user_profile['hometown']['name'] : '',//TODO to be changed in the future - zip code maybe?
		"current_location" => isset($user_profile['location']) ? $user_profile['location']['name'] : '',//TODO to be changed in the future - zip code maybe?
		"relationship_id" => isset($user_profile['relationship_status']) ? SNHandler::getRelationShipIdFromString($user_profile['relationship_status']) : '0'
	);
	$UserService->insertUserNetworkProfile($user_profile_data);
	
	
	
	//User profile objects from facebook and insert them into user_object,object and object_url tables
	getAndInsertUserProfileObjectsFromFB($user_data);
	
	
	
	//User feeds
	$feeds = $SNFacebookService->getUserFeeds($user_data);
	if ($feeds['data']) {
		echo "Inserting Feeds :";
		$feeds = $feeds['data'];
		$data = array (
			"feeds" => $feeds,
			"user_id" => $user_id,
			"network_id" => 2
		);
		searchForUrlsAndInsertFeeds($data);
	}
	
	
	//User friends
	$data = array(
		"limit" => NUMBER_OF_FB_FRIENDS_TO_FETCH_LIMIT
	);
	$friends = $SNFacebookService->getUserFriends($data);
	if ($friends) {
		echo "Inserting User Friends into user_network table \n";
		echo "Inserting User Friends into network_friend table \n";
		echo "Inserting User Friends feeds/urls into network_post_url table :";
		
		foreach($friends as $value){
			//First insert the friend into the user_network table with an empty acces token
			$user_friend_data = array (
				"access_token" => "",
				"network_id"	=> 2,
				"network_user_id" => $value["id"],
				"screen_name" => '', //We never get the username with getUserFriends func but i put it for insertUserNetwork
	     		"name" =>	isset($value["name"]) ? $value["name"] : ''
			);					
			$UserService->insertUserNetwork($user_friend_data);


			//Friend profile info from FB and store it into user_network_data table
			$friend_profile = $SNFacebookService->getUserProfile($user_friend_data);
			echo "Inserting User friend profile data into user_network_data table \n";
			$recent_education_arr = isset($friend_profile['education']) ? array_slice($friend_profile['education'],-1,1) : '';
			$friend_profile_data = array(
				"network_id" => 2,
				"network_user_id" => $value["id"],
				"age" => isset($friend_profile['birthday']) ? SNHandler::getAgeFromBirthday($friend_profile['birthday']) : '0',
				"gender" => isset($friend_profile['gender']) ? SNHandler::getGenderIdFromString($friend_profile['gender']) : '0',
				"recent_education" => isset($recent_education_arr[0]['type']) ? StringHelper::normalizeString($recent_education_arr[0]['type']) : '',
				"highest_education" => isset($recent_education_arr[0]['type']) ? $recent_education_arr[0]['type'] : '',//TODO code the logic for getting from facebook the highest education pursued by the user or find by ourselves
				"from_location" => isset($friend_profile['hometown']) ? $friend_profile['hometown']['name'] : '',//TODO to be changed in the future - zip code maybe?
				"current_location" => isset($friend_profile['location']) ? $friend_profile['location']['name'] : '',//TODO to be changed in the future - zip code maybe?
				"relationship_id" => isset($friend_profile['relationship_status']) ? SNHandler::getRelationShipIdFromString($friend_profile['relationship_status']) : '0'
			);
			$UserService->insertUserNetworkProfile($friend_profile_data);
			
			
			//Friend profile objects from facebook and insert them into user_object,object and object_url tables
			getAndInsertUserProfileObjectsFromFB($user_friend_data);
			
			
			//Insert the user friend in the network_friend table
			$friend_data = array(
				'user_id_from' => $user_id,
				'user_id_to' => $value['id'],
				'network_id' => 2
			);
			$UserService->insertUserNetworkFriend($friend_data);
			
			
			//Insert the friend feeds into the network_post_url table
			$data = array(
				"network_user_id" => $value['id'],
				"limit" => NUMBER_OF_FB_FRIENDS_FEEDS_TO_FETCH_LIMIT
			);
			$friend_feeds = $SNFacebookService->getUserFeeds($data);
			$friend_feeds = $friend_feeds['data'];
			$data = array (
				"feeds" => $friend_feeds,
				"user_id" => $value['id'],
				"network_id" => 2
			);
			searchForUrlsAndInsertFeeds($data);
			
		}
	}*/
}
$out = ob_get_contents();
ob_end_clean();
file_put_contents('/tmp/start_fetching_facebook_data.log',print_r($out,true));

//Get the FB feed stream of a user and search for urls in the post or in the link and insert them into network_post_url table
function searchForUrlsAndInsertFeeds($data) {
	global $UserService;
	
	$feeds = $data['feeds'];
	$user_id = $data['user_id'];
	$network_id = $data['network_id'];
	
	//INFO : First we get the urls from the message (post) and insert them into network_post_url table
	//then we see if there is any url in the 'link' part, if yes and we try to insert it (mysql will ignore if already inserted..) 
	foreach ($feeds as $feed) {
		   
		if (isset($feed['message'])) {//text of the post
			//We get the urls from the post text
			$messageClean = str_replace("/#!/","/",$feed['message']); //FB inserts some weird characters in the urls, we remove them (create fb util function?)
			$urlsFromPost = SNHandler::getUrlsFromText($messageClean);
			if (!empty($urlsFromPost)) {
				//We insert the urls one by one in db
				foreach ($urlsFromPost as $urlFromPost) {
					if (!isUrlFromFBDomain($urlFromPost)) {//We get only the urls that are not from facebook domain FOR NOW
						$url_data = array( 
						'network_user_id' => $user_id,
						'post_id' => $feed['id'],//is like 'user_id_post_id'
						'url' => $urlFromPost,
						'network_id' => $network_id
						);
						echo "-";
						$UserService->insertUserNetworkUrls($url_data);
						$url_data = array();//clear
					}
				}
			}
		}
		
		if (isset($feed['link'])) {//'link' is provided by FB, it always contains the first url, in case it is different or there is no url in the message we try to insert it..
			echo "\n ".$feed['link'];
			$linkClean = str_replace("/#!/","/",$feed['link']); //FB inserts some weird characters in the urls, we remove them
			if (!isUrlFromFBDomain($linkClean)) {//We get only the urls that are not from facebook domain FOR NOW
				$url_data = array( 
					'network_user_id' => $user_id,
					'post_id' => $feed['id'],//is like 'user_id_post_id'
					'url' => $linkClean,
					'network_id' => $network_id
				);
				echo ".";
				$UserService->insertUserNetworkUrls($url_data);
			}
		}
	}
}

//this function test if a url is from fb domain
function isUrlFromFBDomain($url) {
	if ($url) {
		//we want to avoid facebook domain pages, like eg. http://www.facebook.com/profile.php?id=64565
		if ((strpos($url, "http://www.facebook.com") === false) && (strpos($url, "http://facebook.com") === false)) {
			return false;
		}
		//we dont want to avoid these pages like http://www.facebook.com/pages/avatar
		if ((strpos($url, "http://www.facebook.com/pages/") !== false) || (strpos($url, "http://facebook.com/pages/") !== false)) { //we want to keep the facebook pages as they could contain valuable info
			return false;
		}
	}
	return true;
}

function getAndInsertUserProfileObjectsFromFB ($user_data) {
	global $ContentService, $SNFacebookService;
	//User Movies - books - music - games - tv shows/series
	//movies
	$user_data['object_type'] = 'movie';
	$user_movies = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_movies['data'])) {
		$user_data['objects'] = $user_movies['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//books
	$user_data['object_type'] = 'book';
	$user_data['objects'] = '';
	$user_books = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_books['data'])) {
		$user_data['objects'] = $user_books['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//music
	$user_data['object_type'] = 'music';
	$user_data['objects'] = '';
	$user_music = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_music['data'])) {
		$user_data['objects'] = $user_music['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//games
	$user_data['object_type'] = 'game';
	$user_data['objects'] = '';
	$user_games = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_games['data'])) {
		$user_data['objects'] = $user_games['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//television
	$user_data['object_type'] = 'television';
	$user_data['objects'] = '';
	$user_television = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_television['data'])) {
		$user_data['objects'] = $user_television['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//activities
	$user_data['object_type'] = 'activity';
	$user_data['objects'] = '';
	$user_activities = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_activities['data'])) {
		$user_data['objects'] = $user_activities['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//interests
	$user_data['object_type'] = 'interest';
	$user_data['objects'] = '';
	$user_interests = $SNFacebookService->getUserObjectFromFB($user_data);
	if (!empty($user_interests['data'])) {
		$user_data['objects'] = $user_interests['data']; 
		print_r($user_data['objects']);
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//TODO other pages the user liked ..see FB api to code it..
}
exit();
?>
