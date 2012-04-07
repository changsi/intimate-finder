<?php
//require getConfigFilePath("config");
require getConfigFilePath("db_config");
require getConfigFilePath("sn_config");
require getLibFilePath("db.driver.MySqlDB");

//init core_platform DB driver
$platform_DB_driver = new MySqlDB();
$platform_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["PLATFORM_DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$platform_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

require getContextFilePath("common.service.IdService");
require getContextFilePath("user.service.UserService");
require getContextFilePath("user.service.UserCategoryService");
require getContextFilePath("user.service.UserFriendService");
require getContextFilePath("tribe.service.TribeService");

$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);
$UserService->setDBDriverForCorePlatform($platform_DB_driver);

$UserCategoryService = new UserCategoryService();
$UserCategoryService->setDBDriverForCorePlatform($platform_DB_driver);

$TribeService = new TribeService();
$TribeService->setDBDriverForLiveSystem($live_DB_driver);

$UserFriendService = new UserFriendService();
$UserFriendService->setDBDriverForLiveSystem($live_DB_driver);
$UserFriendService->setDBDriverForCorePlatform($platform_DB_driver);

require getPresentationControllerFilePath("default");

?>
