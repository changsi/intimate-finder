<?php


require getLibFilePath('db.DB');

class MySqlDB extends DB { 
	private  $link; 
	private $ok; 
	private $db_name;
	
	public function connect($server='', $db_name='', $username='', $password='', $port='', $persistent=false, $new_link=false, $client_flags=0) {
		$this->ok = false;

		try{
			//bug: $persistent == "false" may not be correct
			//$persistent = !$persistent || $persistent == "false" || $persistent == "0" || $persistent == "null" ? false : true;
			//$new_link = !$new_link || $new_link == "false" || $new_link == "0" || $new_link == "null" ? false : true;
			//$client_flags = !$client_flags || $client_flags == "false" || $client_flags == "0" || $client_flags == "null" ? 0 : $client_flags;
			
			$server_port = is_numeric($port) ? $server.":".$port : $server;
			
			if($persistent) {
				$this->link = mysql_pconnect($server_port, $username, $password, $client_flags); 
			}
			else {
				$this->link = mysql_connect($server_port, $username, $password, $new_link, $client_flags); 
			}
			
			if($db_name) {
				$this->selectDB($db_name);
			}
			
		}catch(Exception $e) {
			Throw $e;
			//launch_exception(new SQLException(1, $e, array($server, $db_name, $username, "", $port, $persistent, $new_link, $client_flags)));
		}
	}
	
	private function selectDB($db_name) {
		try {
			//$this->ok = $this->query('use ' . $db_name) ? true : false;
			$this->ok = mysql_select_db($db_name, $this->link);
			$this->db_name = $db_name;
			return $this->ok;
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(2, $e, array($db_name)));
		}
	}
	 
	public function close() { 
		try {
			return mysql_close($this->link);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(3, $e));
		}
	} 
	
	public function setCharset($charset = "utf8") {
		return mysql_set_charset($charset, $this->link);
	}
	 
	public function ok() { 
		return $this->ok; 
	} 
	
	public function errno() {
		try {
			return mysql_errno($this->link); 
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(4, $e));
		}
	} 
	
	public function error() {
		try {
			return mysql_error($this->link); 
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(5, $e));
		}
	} 
	
	public function query($sql, $options = false) {
		try {
			if(isset($options["LIMIT"]) && is_numeric($options["LIMIT"])) {
				$sql = "SELECT * FROM (" . $sql . ") AS QUERY_WITH_PAGINATION LIMIT " . ($options["START"] ? $options["START"] : 0) . ", " . $options["LIMIT"];
			}
		
			return mysql_query($sql, $this->link);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(6, $e, array($sql)));
		}
	} 
	 
	public function freeResult($result) {
		try {
			return mysql_free_result($result);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(7, $e, array($result)));
		}
	} 
	 
	public function fetchArray($result, $array_type = false) {
		try {
			$array_type = $array_type ? $array_type : MYSQL_BOTH;
			return mysql_fetch_array($result, $array_type);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(8, $e, array($result, $array_type)));
		}
	} 
	
	public function fetchRow($result) {
		try {
			return mysql_fetch_row($result);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(9, $e, array($result)));
		}
	} 
	 
	public function fetchAssoc($result) {
		try {
			return mysql_fetch_assoc($result);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(10, $e, array($result)));
		}
	} 
	 
	public function fetchObject($result) {
		try {
			return mysql_fetch_object($result);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(11, $e, array($result)));
		}
	} 
	 
	public function fetchField($result, $offset) {
		try {
			return mysql_fetch_field($result, $offset);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(12, $e, array($result, $offset)));
		}
	} 
	
	public function listDBs() {
		$dbs = array();
		
		$result = mysql_list_dbs($this->link);
		if($result) {
			while($row = $this->fetchObject($result)) {
			     $dbs[] = $row->Database;
			}
			$this->freeResult($result);
		}
		return $dbs;
	}
	
	public function listTables($db_name = false) {
		$tables = array();
		
		$db_name = $db_name ? $db_name : $this->db_name;
		
		$result = $this->query("SHOW TABLES FROM $db_name");
		if($result) {
			while($row = $this->fetchRow($result)) {
			    $tables[] = $row[0];
			}
			$this->freeResult($result);
		}
		return $tables;
	}
	
	public function listTableFields($table) {
		$fields = array();
		
		$result = $this->getData("SHOW COLUMNS FROM {$table}");
		if(isset($result["RESULT"])) {
			for($i = 0; $i < count($result["RESULT"]); ++$i) {
				$field = $result["RESULT"][$i];
				
				$field_type = $field["Type"];
				$type = explode("(", $field_type);
				$length = explode(")", $type[1]);
				
				$fields[ $field["Field"] ] = array(
					"TYPE" => $type[0],
					"LENGTH" => is_numeric($length[0]) ? $length[0] : false,
					"NULL" => $field["Null"] == "NO" ? false : true,
					"PRIMARY_KEY" => $field["Key"] == "PRI" ? true : false
				);
			}
		}
		
		return $fields;
	}
	
	public function numRows($result) {
		try {
			return mysql_num_rows($result);
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(13, $e, array($result)));
		}
	} 
	
	public function numFields($result) {
		try {
			return mysql_num_fields($result); 
		}catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(14, $e, array($result)));
		}
	} 

    	public function getInsertedId($seq_name = false) {
    		return mysql_insert_id($this->link);
	}
}
?>
