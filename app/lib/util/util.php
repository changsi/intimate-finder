<?php

function destroySession() {
	foreach($_SESSION as $key => $value) {
		$_SESSION[$key] = false;
		unset($_SESSION[$key]);
	}
	session_destroy();
}

function destroyTwitterSession() {
	return destroySession();
}

function errorLog($errorMessage,$filePath='') {
	if ($errorMessage) {
		if (!empty($filePath)) {
			return error_log($errorMessage, 3, $filePath);
		}
		else {
			return error_log($errorMessage, 3, TEMP_PATH."error.log");
		}
	}
	else {
		echo 'ERROR - No error message specified in error_log function';
	}
	return false;
}
?>
