<?php

	define("APP_PATH", dirname(dirname(__FILE__)));
	
	require APP_PATH . "/lib/cms/init.php";
	require getConfigFilePath("config");
	require getLibFilePath("db.driver.MySqlDB");
	require getLibFilePath("util.DistanceHelper");
	
	require getContextFilePath("sn.service.SNFacebookService");
	require getContextFilePath("location.service.LocationService");
	require getContextFilePath("user.service.UserService");
	
	
	require getConfigFilePath("db_config");
	
	
	//init live system DB driver
	$live_DB_driver = new MySqlDB();
	$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], '', false, true);
	$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
	
	$UserService = new UserService();
	$UserService->setDBDriverForLiveSystem($live_DB_driver);
	
	if($UserService->isRegisteredUser(array('user_id'=>100001563590428))){
		echo "true\n";
	}else{
		echo "false\n";
	}
	
	
	if($UserService->isRegisteredUser(array('user_id'=>100001504551254))){
		echo "true\n";
	}else{
		echo "false\n";
	}
	
	
	
	
	
	
?>