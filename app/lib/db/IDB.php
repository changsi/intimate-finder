<?php


interface IDB 
{ 
	public function getData($sql, $options = false);
	public function setData($sql);
} 
?>
