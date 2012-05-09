<?php

session_unset();
session_destroy();

header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
die();

?>