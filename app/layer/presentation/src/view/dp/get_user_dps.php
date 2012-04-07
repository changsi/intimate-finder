<?php

if ($dynamic_dps) {
	/*
	echo "<pre>";
	print_r($dynamic_dps);
	echo "</pre>";
	*/
	echo "var data = " . json_encode($dynamic_dps) . ";";
}
else {
	echo "No data to display.";
}


?>
