<?php
/*
This is the file which checks if the user is already logged in.
If no redirect him to the sn/facebook/login.php page.
If yes, update the access_token (just in case) and:
	- then redirect him to the correspondent page.
	- or simply shows for now a simply menu with all the options and everytime that we click in some menu, executes an ajax request, parse the returned json object and display it. (This is done in javascript off course ;-)
*/

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$login_user_id = isset($_SESSION["network_user_id"]) ? $_SESSION["network_user_id"] : NULL;

if (!isset($access_token) || !isset($login_user_id)) {
    header("Location: ".HOST_PREFIX."/sn/facebook/login");
    die();
}

?>
