<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */
define('APP_PATH', dirname(dirname(dirname(dirname(__FILE__)))));

require APP_PATH . "/lib/cms/init.php";
require getLibFilePath('cms.Controller');

$url = isset($_GET["url"]) ? $_GET["url"] : "";

if (empty($url) && $argc > 1) {
	$url = $argv[1];
}

$controller_setings = Controller::getPresentationControllerSettings($url);

if ($controller_setings['OBJ_FILE_PATH']) {
	require $controller_setings['OBJ_FILE_PATH'];
}
else {
	require getPresentationViewFilePath("default.error");
}
?>
