<?php
//require getConfigFilePath("config");
require getConfigFilePath("db_config");
require getConfigFilePath("sn_config");
require getLibFilePath("db.driver.MySqlDB");

//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], "", false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);

require getContextFilePath("common.service.IdService");

require getContextFilePath("tribe.service.TribeService");

require getContextFilePath("content.service.ContentService");

require getContextFilePath("sn.service.SNFacebookService");

require getContextFilePath("user.service.UserService");


$ContentService = new ContentService();
$ContentService->setDBDriverForLiveSystem($live_DB_driver);
$TribeService = new TribeService();
$TribeService->setDBDriverForLiveSystem($live_DB_driver);
$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);
$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);

require getPresentationControllerFilePath("default");

?>
