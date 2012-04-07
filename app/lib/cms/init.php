<?php

function getImportFilePath($path, $extension) {
	$path = str_replace(".", "/", $path);
	$extension = $extension ? "." . $extension : "";
	
	return APP_PATH . "/" . $path . $extension;
}

function getLibFilePath($path, $extension = "php") {
	return getImportFilePath("lib." . $path, $extension);
}

function getRuleFilePath($path, $extension = "php") {
	return getImportFilePath("layer.rule." . $path, $extension);
}

function getContextFilePath($path, $extension = "php") {
	return getImportFilePath("layer.context." . $path, $extension);
}

function getConfigFilePath($path, $extension = "php") {
	return getImportFilePath("config." . $path, $extension);
}

function getPresentationControllerFilePath($path, $extension = "php") {
	return getImportFilePath("layer.presentation.src.controller." . $path, $extension);
}

function getPresentationEntityFilePath($path, $extension = "php") {
	return getImportFilePath("layer.presentation.src.entity." . $path, $extension);
}

function getPresentationViewFilePath($path, $extension = "php") {
	return getImportFilePath("layer.presentation.src.view." . $path, $extension);
}

function getScriptFilePath($path, $extension = "php") {
	return getImportFilePath("script." . $path, $extension);
}
?>
