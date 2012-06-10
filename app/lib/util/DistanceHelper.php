<?php

function calculate_distance($lat1, $lon1, $lat2, $lon2){
	$R = 6371; // km

	$dLat = toRad($lat2-$lat1);
	$dLon = toRad($lon2-$lon1);
	$lat1 = toRad($lat1);
	$lat2 = toRad($lat2);

	$a = sin($dLat/2) * sin($dLat/2) +
	sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
	$d = $R * $c;
	return $d/1.6;
}

function toRad($value) {
	/** Converts numeric degrees to radians */
	$pi = pi();
	return $value * $pi / 180;
}

?>