<?php

/* configuration for scripts */
//$SAVE_FOLDER_PATH = "/home/envio/projects/targeted_advertising/trunk/client/facebook_app/data/facebook/";
$SAVE_URL_FOLDER_PATH = "/home/envio/Desktop/tmp/user_history_parser/to_parse/";
$SAVE_FRIEND_FOLDER_PATH = "/home/envio/Desktop/tmp/friend_parser/to_parse/";
$SCRIPT_TIMEOUT = 60 * 10;

$CP_URL_BUCKET_SIZE = 1000;
$CP_FRIEND_BUCKET_SIZE = 100;
$CP_FRIEND_REMOVE_BUCKET_SIZE = 100;
$PROJECT_FOLDER = "/home/envio/projects/targeted_advertising/trunk/java/";
$CP_JAR_PATH = "/home/envio/Desktop/targeted_advertising.jar";
$CONFIG_PATH = $PROJECT_FOLDER . "data/config/config_test.cfg";
$TEMPLATE_FOLDER = $PROJECT_FOLDER . "data/template/";

$CP_RUN_ALL_MODULES_PARAM = "IndexMain controller.RunAllModulesController RunAllModulesServiceViaJava %s " . $CONFIG_PATH . " " . $TEMPLATE_FOLDER . "user_history_twitter_spiral.tpl " . $TEMPLATE_FOLDER . "crawler_tags_test.tpl"; 
$CP_RUN_FRIEND_MODULE_PARAM = "IndexMain controller.FriendParserController ServerAddFriendParserService %s " . $CONFIG_PATH . " " . $TEMPLATE_FOLDER . "add_friend_test.tpl";
$CP_RUN_REMOVE_FRIEND_MODULE_PARAM = "IndexMain controller.FriendParserController ServerRemoveFriendParserServic %s " . $CONFIG_PATH . " " . $TEMPLATE_FOLDER . "remove_friend_test.tpl";
$CP_RUN_USER_HISTORY_PARSER_MODULE_PARAM = "IndexMain controller.UserHistoryParserController ServerUserHistoryParserService %s " . $CONFIG_PATH . " " . $TEMPLATE_FOLDER . "user_history_twitter_spiral.tpl";

$USER_USER_BUCKET_SIZE = define ("USER_USER_BUCKET_SIZE", 2);
?>
