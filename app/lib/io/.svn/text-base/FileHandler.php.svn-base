<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

class FileHandler {
	private $file_path;
	private $fp;
	
	public function __construct($file_path) {
		$this->file_path = $file_path;
	}
	
	public function exists() {
		return file_exists($this->file_path);
	}
	
	public function open($mode) {
		return $this->fp = fopen($this->file_path, $mode);
	}
	
	public function create() {
		return $this->fp = fopen($this->file_path, 'w+');
	}
	
	public function close() {
		return fclose($this->fp);
	}
	
	//Writing to a network stream may end before the whole string is written. Return value of fwrite() may be checked: 
	public function write($string) {
		$fwrite = 0;
		
		for ($written = 0; $written < strlen($string); $written += $fwrite) {
			$fwrite = fwrite($this->fp, substr($string, $written));
			
			if ($fwrite === false) {
				return $written;
			}
		}
		
		return $written;
	}
	
	public function read() {
		$str = "";
		
		if ($this->fp) {
		    while (!feof($this->fp)) {
		    		$str .= fgets($this->fp, 4096) ;
		    }
		}
		
		return $str;
	}
	
	public function resetPointer() {
		return rewind($this->fp);		
	}
	
	public function setPermission($perm) {
	return exec("chmod"  . $perm . " " . $this->file_path);
	}
}
?>
