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
require getContextFilePath("sn.service.SNFacebookService");
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
//ob_start();
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

$numberOfFeedsByFriends = array();

if (isset($token[0]['access_token'])) {
	$access_token = $token[0]['access_token'];
	echo 'User token (fromDB) :'.$access_token." \n "; 
	$SNFacebookService->setAccessToken($token[0]);//set the access token from DB to FB API to access user info
	
	
	//User personal info from facebook and insert it into user_network_data table
	$user_data = array(
		"network_user_id" => $user_id,
		"limit" => NUMBER_OF_FB_FEEDS_TO_FETCH_LIMIT
	);

	$fb_data = $SNFacebookService->getEverythingExceptFriendsFeeds($user_data);
	
	if ($fb_data) {
		//////// USER ////////
		$user_profile = json_decode($fb_data[0]['body'], true, 512);
		$user_feeds = json_decode($fb_data[1]['body'], true, 512);
		
		$user_profile_objects['movies'] = json_decode($fb_data[2]['body'], true, 512);
		$user_profile_objects['books'] = json_decode($fb_data[3]['body'], true, 512);
		$user_profile_objects['music'] = json_decode($fb_data[4]['body'], true, 512);
		$user_profile_objects['games'] = json_decode($fb_data[5]['body'], true, 512);
		$user_profile_objects['television'] = json_decode($fb_data[6]['body'], true, 512);
		$user_profile_objects['activities'] = json_decode($fb_data[7]['body'], true, 512);
		$user_profile_objects['interests'] = json_decode($fb_data[8]['body'], true, 512);
		
		$user_links = json_decode($fb_data[9]['body'],true, 512);
		$user_links = isset($user_links['data']) ? $user_links['data'] : '';
		
		$user_feeds = isset($user_feeds['data']) ? $user_feeds['data'] : '';
		
		$user_profile_objects['movies'] = isset($user_profile_objects['movies']['data']) ? $user_profile_objects['movies']['data'] : '';
		$user_profile_objects['books'] = isset($user_profile_objects['books']['data']) ? $user_profile_objects['books']['data'] : '';
		$user_profile_objects['music'] = isset($user_profile_objects['music']['data']) ? $user_profile_objects['music']['data'] : '';
		$user_profile_objects['games'] = isset($user_profile_objects['games']['data']) ? $user_profile_objects['games']['data'] : '';
		$user_profile_objects['television'] = isset($user_profile_objects['television']['data']) ? $user_profile_objects['television']['data'] : '';
		$user_profile_objects['activities'] = isset($user_profile_objects['activities']['data']) ? $user_profile_objects['activities']['data'] : '';
		$user_profile_objects['interests'] = isset($user_profile_objects['interests']['data']) ? $user_profile_objects['interests']['data'] : '';
		
		$numberOfFeedsByFriends[$user_profile['name']] =  count($user_feeds);

		//User Profile
		if ($user_profile) {
			parseAndInsertUserProfile($user_profile);
		}
		else {
			echo "ERROR 1\n";
		}

		
		//User feeds
		if ($user_feeds) {
			parseAndInsertUserFeeds($user_feeds, $user_id);
		}
		else {
			echo "ERROR 2 \n";
		}
		
		//User profile objects from facebook, insert them into user_object,object and object_url tables
		if ($user_profile_objects) {
			$user_data['all_objects'] = $user_profile_objects;
			insertUserProfileObjectsFromFB($user_data);
		}
		else {
			echo "ERROR 3 \n";
		}
		
		if ($user_links) {
			parseAndInsertUserLinks($user_links, $user_id);
		}
		else {
			echo "ERROR 4 \n";
		}
			
		
		//////// Friends ///////
		$user_friends = json_decode($fb_data[10]['body'], true, 512);
		if ($user_friends) {
			$user_friends = $user_friends['data'];
			parseAndInsertFriends($user_friends);
			
			//do an other request for each bucket of 5 friends fetch their feeds,profile info
			$friendsCount = count($user_friends);
			echo "FRIENDS COUNT: $friendsCount \n";
			$data = array(
				"friends" => $user_friends,
			);
			
			$numberOfFullBatches = floor($friendsCount/5);
			$numberOfItemsForLastBatch = $friendsCount%5;
			echo "numberOfFullBatches $numberOfFullBatches \n";
			echo "numberOfItemsForLastBatch $numberOfItemsForLastBatch \n";
			$k=1;
			if ($numberOfFullBatches > 0) {
				for ($j = 0; $j < ($numberOfFullBatches*5); $j = $j + 5) {
					$data["start"] = $j;
					$data["limit"] = 5;
					echo " \n Executing batch number : ".$k." - start: ".$data["start"]." - limit: ".$data["limit"]." \n";
					$k++;
					$fb_friend_data = $SNFacebookService->getFriendProfileInfoAndFeeds($data);
					
					if ($fb_friend_data) {
						$dataCount = floor(count($fb_friend_data)/10);//we get back an array of 10x X elements so we want to know how manytimes we are looping
						for ($i = 0; $i < ($dataCount*10); $i = $i + 10) {
							$friend_profile = json_decode($fb_friend_data[$i]['body'], true, 512);
							$friend_feeds = json_decode($fb_friend_data[$i+1]['body'], true, 512);
							
							$friend_profile_objects['movies'] = json_decode($fb_friend_data[$i+2]['body'], true, 512);
							$friend_profile_objects['books'] = json_decode($fb_friend_data[$i+3]['body'], true, 512);
							$friend_profile_objects['music'] = json_decode($fb_friend_data[$i+4]['body'], true, 512);
							$friend_profile_objects['games'] = json_decode($fb_friend_data[$i+5]['body'], true, 512);
							$friend_profile_objects['television'] = json_decode($fb_friend_data[$i+6]['body'], true, 512);
							$friend_profile_objects['activities'] = json_decode($fb_friend_data[$i+7]['body'], true, 512);
							$friend_profile_objects['interests'] = json_decode($fb_friend_data[$i+8]['body'], true, 512);
							$friend_links = json_decode($fb_friend_data[$i+9]['body'], true, 512);
							$friend_links = isset($friend_links['data']) ? $friend_links['data'] : '';
							
							$friend_feeds = isset($friend_feeds['data']) ? $friend_feeds['data'] : '';
		
							$friend_profile_objects['movies'] = isset($friend_profile_objects['movies']['data']) ? $friend_profile_objects['movies']['data'] : '';
							$friend_profile_objects['books'] = isset($friend_profile_objects['books']['data']) ? $friend_profile_objects['books']['data'] : '';
							$friend_profile_objects['music'] = isset($friend_profile_objects['music']['data']) ? $friend_profile_objects['music']['data'] : '';
							$friend_profile_objects['games'] = isset($friend_profile_objects['games']['data']) ? $friend_profile_objects['games']['data'] : '';
							$friend_profile_objects['television'] = isset($friend_profile_objects['television']['data']) ? $friend_profile_objects['television']['data'] : '';
							$friend_profile_objects['activities'] = isset($friend_profile_objects['activities']['data']) ? $friend_profile_objects['activities']['data'] : '';
							$friend_profile_objects['interests'] = isset($friend_profile_objects['interests']['data']) ? $friend_profile_objects['interests']['data'] : '';
							
							$numberOfFeedsByFriends[$friend_profile['name']] =  count($friend_feeds);
							/*print_r($fb_friend_data);
							print_r($friend_profile);
							print_r($friend_feeds);
							print_r($friend_profile_objects);
							*/
							
							
							if ($friend_profile) {
								//Friend Profile
								parseAndInsertUserProfile($friend_profile);
								
								//Friend feeds
								parseAndInsertUserFeeds($friend_feeds, $friend_profile["id"]);
								parseAndInsertUserLinks($friend_links, $friend_profile["id"]);
								
								//Friend profile objects from facebook, insert them into user_object,object and object_url tables
								$user_friend_data = array(
									"network_user_id" => $friend_profile["id"],
									"all_objects" => $friend_profile_objects
								);
								insertUserProfileObjectsFromFB($user_friend_data);
								$expiry_date_timestamp = mktime()+FREQUENCY_TO_UPDATE_FACEBOOK_DATA;
								$expiry_date = date("Y-m-d H:i:s", $expiry_date_timestamp);
								updateUserExpiryDate($expiry_date,$friend_profile["id"]);
							}
							else {
								echo "ERROR 6 \n";
							}
						}						
					}
					else {
						echo "ERROR 5\n";
					}
				}
			}
			if ($numberOfItemsForLastBatch > 0) {
				$data["start"] = $numberOfFullBatches*5;
				$data["limit"] = $numberOfItemsForLastBatch;
				
				echo "Executing last batch. \n";
				$fb_friend_data = $SNFacebookService->getFriendProfileInfoAndFeeds($data);
					
				if ($fb_friend_data) {
					$dataCount = floor(count($fb_friend_data)/10);//we get back an array of 9x X elements so we want to know how manytimes we are looping
					for ($i = 0; $i < ($dataCount*10); $i = $i + 10) {
						$friend_profile = json_decode($fb_friend_data[$i]['body'], true, 512);
						$friend_feeds = json_decode($fb_friend_data[$i+1]['body'], true, 512);
						
						$friend_profile_objects['movies'] = json_decode($fb_friend_data[$i+2]['body'], true, 512);
						$friend_profile_objects['books'] = json_decode($fb_friend_data[$i+3]['body'], true, 512);
						$friend_profile_objects['music'] = json_decode($fb_friend_data[$i+4]['body'], true, 512);
						$friend_profile_objects['games'] = json_decode($fb_friend_data[$i+5]['body'], true, 512);
						$friend_profile_objects['television'] = json_decode($fb_friend_data[$i+6]['body'], true, 512);
						$friend_profile_objects['activities'] = json_decode($fb_friend_data[$i+7]['body'], true, 512);
						$friend_profile_objects['interests'] = json_decode($fb_friend_data[$i+8]['body'], true, 512);
						$friend_links = json_decode($fb_friend_data[$i+9]['body'], true, 512);
						$friend_links = isset($friend_links['data']) ? $friend_links['data'] : '';
						
						$friend_feeds = isset($friend_feeds['data']) ? $friend_feeds['data'] : '';
	
						$friend_profile_objects['movies'] = isset($friend_profile_objects['movies']['data']) ? $friend_profile_objects['movies']['data'] : '';
						$friend_profile_objects['books'] = isset($friend_profile_objects['books']['data']) ? $friend_profile_objects['books']['data'] : '';
						$friend_profile_objects['music'] = isset($friend_profile_objects['music']['data']) ? $friend_profile_objects['music']['data'] : '';
						$friend_profile_objects['games'] = isset($friend_profile_objects['games']['data']) ? $friend_profile_objects['games']['data'] : '';
						$friend_profile_objects['television'] = isset($friend_profile_objects['television']['data']) ? $friend_profile_objects['television']['data'] : '';
						$friend_profile_objects['activities'] = isset($friend_profile_objects['activities']['data']) ? $friend_profile_objects['activities']['data'] : '';
						$friend_profile_objects['interests'] = isset($friend_profile_objects['interests']['data']) ? $friend_profile_objects['interests']['data'] : '';
						
						
						$numberOfFeedsByFriends[$friend_profile['name']] =  count($friend_feeds);
						/*
						print_r($friend_profile);
						print_r($friend_feeds);
						print_r($friend_profile_objects);
						*/
						
						
						if ($friend_profile) {
							//Friend Profile
							parseAndInsertUserProfile($friend_profile);
							
							//Friend feeds
							parseAndInsertUserFeeds($friend_feeds, $friend_profile["id"]);
							parseAndInsertUserLinks($friend_links, $friend_profile["id"]);
							
							//Friend profile objects from facebook, insert them into user_object,object and object_url tables
							$user_friend_data = array(
								"network_user_id" => $friend_profile["id"],
								"all_objects" => $friend_profile_objects
							);
							insertUserProfileObjectsFromFB($user_friend_data);
							 
							$expiry_date_timestamp = mktime()+FREQUENCY_TO_UPDATE_FACEBOOK_DATA;
							$expiry_date = date("Y-m-d H:i:s", $expiry_date_timestamp);
							updateUserExpiryDate($expiry_date,$friend_profile["id"]);
						}
						else {
							echo "ERROR 8 \n";
						}
						
						
					}
					
				}
				else {
					echo "ERROR 7\n";
				}
			}
			
			
		}
		else {
			echo "ERROR 9\n";
		}
	}
	else {
		echo "An error occured while trying to fetch user FB data";
	}
}
$expiry_date_timestamp = mktime()+FREQUENCY_TO_UPDATE_FACEBOOK_DATA;
$expiry_date = date("Y-m-d H:i:s", $expiry_date_timestamp);
updateUserExpiryDate($expiry_date,$user_id);
echo "Feeds number array \n";
print_r($numberOfFeedsByFriends);

echo "Number of users fetched :".count($numberOfFeedsByFriends);

//$out = ob_get_contents();
//ob_end_clean();
//file_put_contents('/tmp/start_fetching_facebook_data.log',print_r($out,true));

//Get the FB feed stream of a user and search for urls in the post or in the link and insert them into network_post_url table
function searchForUrlsAndInsertFeeds($data) {
	global $UserService;
	
	$feeds = $data['feeds'];
	$user_id = $data['user_id'];
	$network_id = $data['network_id'];
	
	//INFO : First we get the urls from the message (post) and insert them into network_post_url table
	//then we see if there is any url in the 'link' part, if yes and we try to insert it (mysql will ignore if already inserted..) 
	foreach ($feeds as $key => $feed) {
	
		if (isset($feed['message'])) {//text of the post
			//We get the urls from the post text
			$messageClean = str_replace("/#!/","/",$feed['message']); //FB inserts some weird characters in the urls, we remove them (create fb util function?)

			$urlsFromPost = SNHandler::getUrlsFromText($messageClean);
			if (!empty($urlsFromPost)) {
				//We insert the urls one by one in db
				foreach ($urlsFromPost as $urlFromPost) {
					if (!isUrlFromFBDomain($urlFromPost) && filterURL($urlFromPost)) {//We get only the urls that are not from facebook domain FOR NOW
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
			
			$linkClean = str_replace("/#!/","/",$feed['link']); //FB inserts some weird characters in the urls, we remove them

			if (!isUrlFromFBDomain($linkClean)) {//We get only the urls that are not from facebook domain FOR NOW
				$url_data = array( 
					'network_user_id' => $user_id,
					'post_id' => $feed['id'],//is like 'user_id_post_id'
					'url' => $linkClean,
					'network_id' => $network_id
				);

				$UserService->insertUserNetworkUrls($url_data);
			}
		}
	}
}

function searchForUrlAndInsertLinks($data) {
	global $UserService;
	
	$links = $data['links'];
	$user_id = $data['user_id'];
	$network_id = $data['network_id'];
	
	foreach ($links as $link) {
		
		if (isset($link['link'])) {//'link' is provided by FB, it always contains the first url, in case it is different or there is no url in the message we try to insert it..
			
			$linkClean = str_replace("/#!/","/",$link['link']); //FB inserts some weird characters in the urls, we remove them
			if (!isUrlFromFBDomain($linkClean)) {//We get only the urls that are not from facebook domain FOR NOW
				$url_data = array( 
					'network_user_id' => $user_id,
					'post_id' => $link['id'],//is like 'user_id_post_id'
					'url' => $linkClean,
					'network_id' => $network_id
				);

				$UserService->insertUserNetworkUrls($url_data);
			}
		}
	}
}

//this function test if a url is from fb domain or not valuable
function isUrlFromFBDomain($url) {
	$bool = true;
	if ($url) {
		//we want to avoid facebook domain pages, like eg. http://www.facebook.com/profile.php?id=64565
		if ((strpos($url, "http://www.facebook.com") === false) && (strpos($url, "http://facebook.com") === false)) {
			//we want to avoid pages that are just data
			if (strpos($url, "http://gdata.youtube.com/") === false) {
				$bool = false;
			}
		} 
		else {
			//we want to keep pages like http://www.facebook.com/pages/avatar
			if ((strpos($url, "http://www.facebook.com/pages/") !== false) || (strpos($url, "http://facebook.com/pages/") !== false)) { //we want to keep the facebook pages as they could contain valuable info
				$bool = false;
			}
		}
		
	}

	return $bool;
}


function parseAndInsertUserProfile ($user_profile) {
	global $ContentService, $SNFacebookService, $UserService;
	echo "Inserting User profile data into user_network_data table \n";
	$education = isset($user_profile['education']) ? json_encode($user_profile['education']): '';
	

	//$recent_education_arr = isset($user_profile['education']) ? array_slice($user_profile['education'],-1,1) : '';
	$user_profile_data = array(
		"network_id" => 2,
		"network_user_id" => $user_profile['id'],
		"age" => isset($user_profile['birthday']) ? SNHandler::getAgeFromBirthday($user_profile['birthday']) : '0',
		"gender" => isset($user_profile['gender']) ? SNHandler::getGenderIdFromString($user_profile['gender']) : '0',
		"education" => $education,
		"from_location" => isset($user_profile['hometown']) ? $user_profile['hometown']['name'] : '',//TODO to be changed in the future - zip code maybe?
		"current_location" => isset($user_profile['location']) ? $user_profile['location']['name'] : '',//TODO to be changed in the future - zip code maybe?
		"email" => isset($user_profile['email']) ? $user_profile['email'] : '',
		"relationship_id" => isset($user_profile['relationship_status']) ? SNHandler::getRelationShipIdFromString($user_profile['relationship_status']) : '0'
	);

	$UserService->insertUserNetworkProfile($user_profile_data);

}

function parseAndInsertUserFeeds ($user_feeds, $user_id) {
	global $ContentService, $SNFacebookService, $UserService;
	
	if ($user_feeds) {
		echo "Inserting Feeds :";
		$data = array (
			"feeds" => $user_feeds,
			"user_id" => $user_id,
			"network_id" => 2
		);
		searchForUrlsAndInsertFeeds($data);
	}
}

function parseAndInsertUserLinks ($user_links, $network_user_id) {
	global $ContentService, $SNFacebookService, $UserService;
	
	if ($user_links) {
		echo "Inserting Links :";
		$data = array (
			"links" => $user_links,
			"user_id" => $network_user_id,
			"network_id" => 2
		);
		searchForUrlAndInsertLinks($data);
	}
}


function insertUserProfileObjectsFromFB ($user_data) {
	global $ContentService, $SNFacebookService, $UserService;
	
	//User Movies - books - music - games - tv shows/series
	//movies
	$user_data['object_type'] = 'movie';
	if (!empty($user_data['all_objects']['movies'])) {
		$user_data['objects'] = $user_data['all_objects']['movies']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//books
	$user_data['object_type'] = 'book';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['books'])) {
		$user_data['objects'] = $user_data['all_objects']['books']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//music
	$user_data['object_type'] = 'music';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['music'])) {
		$user_data['objects'] = $user_data['all_objects']['music']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//games
	$user_data['object_type'] = 'game';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['games'])) {
		$user_data['objects'] = $user_data['all_objects']['games']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//television
	$user_data['object_type'] = 'television';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['television'])) {
		$user_data['objects'] = $user_data['all_objects']['television']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//activities
	$user_data['object_type'] = 'activity';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['activities'])) {
		$user_data['objects'] = $user_data['all_objects']['activities']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//interests
	$user_data['object_type'] = 'interest';
	$user_data['objects'] = '';
	if (!empty($user_data['all_objects']['interests'])) {
		$user_data['objects'] = $user_data['all_objects']['interests']; 
		$ContentService->insertObject($user_data);
		$ContentService->insertUserObject($user_data);
	}
	//TODO other pages the user liked ..see FB api to code it..
}

//this function is wrong, url with top level domains like .asia wont be considered as urls
function filterURL ($url) {
	if(startsWith($url, "http") || startsWith($url, "https") || startsWith($url, "www") || startsWith($url, "ftp")){
		return true;
	}
	$pieces = explode(".", $url);
	if(isset($pieces) && sizeof($pieces)>=3){
		if(strlen($pieces[sizeof($pieces)-1])>3){
			return false;
		}
		foreach($pieces as $value){
			if($value === ""){
				return false;
			}
		}
		return true;
	}
	return false;
}

function startsWith($string, $prefix) {
    $length = strlen($prefix);
    return (substr($string, 0, $length) === $prefix);
}

function parseAndInsertFriends ($user_friends) {
	global $user_id, $ContentService, $SNFacebookService, $UserService;
	
	//First put the control flag to 1 for all the friends present previously in db
	/*
	CALL JP
	$data = array(
		"network_id"	=> 2,
		"network_user_id" => $friend["id"],
	);
	$UserFriendService->updateControlFlagInUserNetworkFriend($data);
	*/
	//Update the control_flag to 2
	
	$friend_data_update = array(
		'user_id_from' => $user_id,
		'network_id' => 2, 
		'control_flag' => 2
	);
	$UserService->updateUserNetworkFriend($friend_data_update);
			
	foreach ($user_friends as $friend) {
		
		//First insert the friend into the user_network table with an empty acces token
		$user_friend_data = array (
			"access_token" => "",
			"network_id"	=> 2,
			"network_user_id" => $friend["id"],
			"screen_name" => '', //We never get the username by doing /me/friends func but i put it for insertUserNetwork
     		"name" =>	isset($friend["name"]) ? $friend["name"] : ''
		);					
		$UserService->insertUserNetwork($user_friend_data);
		
		//Insert the user friend in the network_friend table
		$friend_data = array(
			'user_id_from' => $user_id,
			'user_id_to' => $friend['id'],
			'network_id' => 2
		);
		$UserService->insertUserNetworkFriend($friend_data);
	}
}

function updateUserExpiryDate($expiry_date, $user_id){
	global $UserService;
	$data = array(
		"expiry_date" => $expiry_date,
		"network_id"	=> 2,
		"network_user_id" => $user_id	
	);
	$UserService->updateUserExpiryDate($data);
	
}
exit();
?>
