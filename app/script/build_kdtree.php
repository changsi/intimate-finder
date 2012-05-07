<?php

function build_kdtree(){
	define("APP_PATH", dirname(dirname(__FILE__)));
	
	require APP_PATH . "/lib/cms/init.php";
	require getConfigFilePath("config");
	require getConfigFilePath("db_config");
	require getLibFilePath("db.driver.MySqlDB");
	require getLibFilePath("io.CurlUtil");
	require getContextFilePath("location.service.LocationService");
	
	//init live system DB driver
	$live_DB_driver = new MySqlDB();
	$live_DB_driver->connect($DB_CONFIG["HOST"], $DB_CONFIG["DBNAME"], $DB_CONFIG["USERNAME"], $DB_CONFIG["PASSWORD"], '', false, true);
	$live_DB_driver->setCharset($DB_CONFIG["ENCODING"]);
	
	//initialize service
	$LocationService = new LocationService();
	$LocationService->setDBDriverForLiveSystem($live_DB_driver);
	$locations = $LocationService->getAllLocation();
	$locations_json = json_encode($locations);
	 
	 $curl = new cURL(false);
	 
	 echo $curl->post('http://localhost:8080/KDTree/build','data='.$locations_json);
	
}

build_kdtree();

?>