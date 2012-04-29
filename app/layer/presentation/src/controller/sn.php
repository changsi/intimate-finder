<?php
//require getConfigFilePath("config");
require getConfigFilePath("db_config");
require getLibFilePath("db.driver.MySqlDB");
require getLibFilePath("util.util");



//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], '', false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
ob_clean();
//require getContextFilePath("common.service.IdService");

require getContextFilePath("sn.service.SNFacebookService");
require getContextFilePath("user.service.UserService");
require getContextFilePath("user.service.UserProgressionService");
require getContextFilePath("user.service.UserFriendService");
require getContextFilePath("location.service.LocationService");
require getContextFilePath("location.service.UserLocationService");


$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);

$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);

$UserProgressionService = new UserProgressionService();
$UserProgressionService->setDBDriverForLiveSystem($live_DB_driver);

$LocationService = new LocationService();
$LocationService->setDBDriverForLiveSystem($live_DB_driver);

$UserLocationService = new UserLocationService();
$UserLocationService->setDBDriverForLiveSystem($live_DB_driver);


$UserFriendService = new UserFriendService();
$UserFriendService->setDBDriverForLiveSystem($live_DB_driver);

require getPresentationControllerFilePath("default");
?>
