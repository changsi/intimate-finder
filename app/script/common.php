<?php 
define("APP_PATH", dirname(dirname(__FILE__)));
	
require APP_PATH . "/lib/cms/init.php";
require getConfigFilePath("config");
require getConfigFilePath("sn_config");
require getConfigFilePath("db_config");
require getConfigFilePath("core_platform_script_config");
require getLibFilePath("db.driver.MySqlDB");
?>
