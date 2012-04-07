<?php
if (isset($_SESSION["network_user_id"]) && isset($_SESSION["network_id"]) && isset($_SESSION["access_token"]) && isset($_SESSION['come_back_later'])) {
}
else {
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
}

?>
