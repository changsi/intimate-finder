<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

class Controller {
	public static function getPresentationControllerSettings($params) {
		$presentation_controller_folder_path = getPresentationControllerFilePath("", "");
		
		return self::getUrlSettings($params, $presentation_controller_folder_path);
	}

	public static function getPresentationEntityViewSettings($controller_setings) {
		$presentation_controller_folder_path = getPresentationControllerFilePath("", "");
		$presentation_entity_folder_path = getPresentationEntityFilePath("", "");
		$presentation_view_folder_path = getPresentationViewFilePath("", "");
		
		if($controller_setings['OBJ_NAME'] && $controller_setings['OBJ_NAME'] != "default") {
			$presentation_entity_folder_path .= "/" . $controller_setings['OBJ_NAME'];
			$presentation_view_folder_path .= "/" . $controller_setings['OBJ_NAME'];
		}

		$presentation_entity_params = "";
		$presentation_view_params = "";

		if ($controller_setings['PARAMS']) {
			$parts = explode("/", $controller_setings['PARAMS']);
			for ($i = 0; $i < count($parts); ++$i) {
				$part = $parts[$i];
				
				if ($part) {
					if (!$presentation_entity_params && is_dir($presentation_entity_folder_path . "/" . $part)) {
						$presentation_entity_folder_path .= "/" . $part;
					}
					else {
						$presentation_entity_params .= ($presentation_entity_params ? "/" : "") . $part;
					}
					
					if (!$presentation_view_params && is_dir($presentation_view_folder_path . "/" . $part)) {
						$presentation_view_folder_path .= "/" . $part;
					}
					else {
						$presentation_view_params .= ($presentation_view_params ? "/" : "") . $part;
					}
				}
			}
		}
		else {
			$presentation_entity_folder_path .= "/default";
			$presentation_view_folder_path .= "/default";
		}
		
		$entity_settings = self::getEntityAndViewUrlSettings($presentation_entity_params, $presentation_entity_folder_path, $presentation_controller_folder_path);
		$view_settings = self::getEntityAndViewUrlSettings($presentation_view_params, $presentation_view_folder_path, $presentation_controller_folder_path);
		
		return array('ENTITY' => $entity_settings, 'VIEW' => $view_settings);
	}
	private static function getEntityAndViewUrlSettings($params, $folder_path, $presentation_controller_folder_path) {
		$main_folder = dirname($presentation_controller_folder_path);
		
		do {
			$settings = self::getUrlSettings($params, $folder_path);
			
			$folder_path = dirname($folder_path);
		} while(!$settings['OBJ_NAME'] && $folder_path && $folder_path != "/" && $folder_path != $main_folder);
		
		return $settings;
	}

	public static function getUrlSettings($url, $obj_folder_path, $default_name = "default", $extension = "php") {
		$extension = $extension ? "." . $extension : "";
		
		$parts = explode("/", $url);
		$obj_name = "";
		$params = "";
		
		for ($i = 0; $i < count($parts); ++$i) {
			$part = trim($parts[$i]);
			
			if ($part) {
				if (!$obj_name) {
					$obj_name = strtolower($part);
				}
				else {
					$params .= ($params ? "/" : "") . $part;
				}
			}
		}
		
		$obj_file_path = $obj_folder_path . "/" . $obj_name . $extension;
		
		if (!$obj_name || !file_exists($obj_file_path)) {
			$params = $obj_name . ($params ? "/" : "") . $params;
			
			$obj_file_path = $obj_folder_path . "/" . $default_name . $extension;
			
			if (file_exists($obj_file_path)) {
				$obj_name = $default_name;
			}
			else {
				$obj_name = "";
				$obj_file_path = "";
			}
		}
		
		$obj_file_path = str_replace("//", "/", $obj_file_path);
		
		return array('OBJ_NAME' => $obj_name, 'OBJ_FILE_PATH' => $obj_file_path, 'PARAMS' => $params);
	}
}
?>
