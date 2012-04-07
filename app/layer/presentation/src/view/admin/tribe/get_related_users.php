<?php

if (!empty($users)) {
	echo "var data = ". json_encode($users)."; ";
}
else {
	echo "var data = '';";
}
?>
