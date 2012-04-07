<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

interface IDB 
{ 
	public function getData($sql, $options = false);
	public function setData($sql);
} 
?>
