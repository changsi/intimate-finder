<?php
//require getConfigFilePath("config");
require getConfigFilePath("db_config");
require getLibFilePath("db.driver.MySqlDB");
require getLibFilePath("util.util");



//init live system DB driver
$live_DB_driver = new MySqlDB();
$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], $DB_CONFIG["PORT"], false, true);
$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
ob_clean();
//require getContextFilePath("common.service.IdService");

require getContextFilePath("sn.service.SNFacebookService");
require getContextFilePath("user.service.UserService");



$SNFacebookService = new SNFacebookService();
$SNFacebookService->setDBDriverForLiveSystem($live_DB_driver);

$UserService = new UserService();
$UserService->setDBDriverForLiveSystem($live_DB_driver);

require getPresentationControllerFilePath("default");
?>
