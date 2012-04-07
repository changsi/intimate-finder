<?php


require getLibFilePath('db.IDB');

abstract class DB implements IDB
{ 
	abstract public function connect($server='',  $db_name='', $username='', $password='', $port='', $persistent=false, $new_link=false);
	abstract public function close(); 
	abstract public function setCharset($charset = 'utf8'); 
	
	abstract public function ok(); 
	abstract public function error(); 
	abstract public function errno(); 

	abstract public function query($sql, $options = false); 
	abstract public function freeResult($result); 
	abstract public function fetchArray($result, $array_type = false); 
	abstract public function fetchRow($result); 
	abstract public function fetchAssoc($result); 
	abstract public function fetchObject($result); 
	abstract public function fetchField($result, $offset); 
	
	abstract public function listDBs(); 
	abstract public function listTables(); 
	abstract public function listTableFields($table); 
	
	abstract public function numRows($result); 
	abstract public function numFields($result);

	abstract public function getInsertedId($seq_name = false); 
	
	public function getData($sql, $options = false) {
	//echo $sql;
		$data = array("FIELDS" => array(), "RESULT" => array(), "ERROR" => false);
		
		try {
			$queries = self::splitSQL($sql);
			for($i = 0; $i < count($queries); ++$i) {
				$query = $queries[$i];
				
				if($query) {
					$result = $this->query($query, $options);
				
					if($result) {
						if(is_resource($result)) {
							$count = $this->numFields($result);
							for($i = 0; $i < $count; ++$i) {
								$data["FIELDS"][] = $this->fetchField($result, $i);
							}
			
							while($row = $this->fetchAssoc($result)) { 
								$data["RESULT"][] = $row;
							} 
							$this->freeResult($result);
						}
					}
					else {
						/*$e = new Error();
						$e->setMessages(array(array($query, $this->error())));
						launch_exception(new SQLException(15, $this->error(), array($query)));
						
						$data["ERROR"] = $e;*/
						$data["ERROR"] = array($query, $this->error());
					}
				}
			}
		} catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(15, $e, array($sql)));
		}
		return $data;
	}
	
	public function setData($sql) {
	//echo $sql;
		try {
			if(!$this->query($sql)) {
				/*$e = new Error();
				$e->setMessages(array(array($sql, $this->error())));
				launch_exception(new SQLException(16, $this->error(), array($sql)));
				return $e;*/
				return array($sql, $this->error());
			}
			return true;
			
		} catch(Exception $e) {
			Throw $e;
			//return launch_exception(new SQLException(16, $e, array($sql)));
		}
		
		return false;
	}
	
	private static function splitSQL($sql) {
		$queries = array();
		
		if(strpos($sql, ";") !== false) {
			$open_double_quotes = false;
			$open_single_quotes = false;
		
			$start = 0;
			$end = strlen($sql);
			
			for($i = 0 ; $i < $end; ++$i) {
				$current = substr($sql, $i, 1);
			
				if($current == '"' && !self::quoteSlashExists($sql, $i) && !$open_single_quotes) {
					$open_double_quotes = !$open_double_quotes;
				}
				elseif($current == "'" && !self::quoteSlashExists($sql, $i) && !$open_double_quotes) {
					$open_single_quotes = !$open_single_quotes;
				}
				elseif($current == ';' && !$open_double_quotes && !$open_single_quotes && $i != $end - 1) {
					$queries[] = substr($sql, $start, ($i - $start) + 1);
					$start = $i + 1;
				}
				
				if($i == $end - 1) {
					$queries[] = substr($sql, $start);
				}
			}
		}
		else {
			$queries[] = $sql;
		}
		return $queries;
	}
	
	private static function quoteSlashExists($sql, $quote_index) {
		$slash_exists = false;
		
		$i = $quote_index - 1;
		while($i >= 0 && substr($sql, $i, 1) == "\\") {
			$slash_exists = !$slash_exists;
			--$i;
		}
		
		return $slash_exists;
	}
} 
?>
