<?php
/*
Receives an USER ID and returns the correspondent categories and affinities from the user_category table (CP DB)

Uses: UserCategoryService->getUserCategories(...)
*/
	$today = $_GET["date"];
	$data = $ItemOfDayService->getAllItemsOfDay($today);
	//echo "var data = " . json_encode($data) . ";";


?>
