<?php

	class DataConnector
	{
		public $_conn;
		
		function __construct()
		{
			$this->_conn = mysql_connect($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpwd"])
				or die("Could not connect: ".mysql_error());
			
			mysql_select_db($GLOBALS["dbname"], $this->_conn);
		}
		
		public function LockTable($table)
		{
			$sql = "LOCK TABLES `".$table."` WRITE";
			return mysql_query($sql);
		}
		
		public function UnlockAllTables()
		{
			$sql = "UNLOCK TABLES";
			return mysql_query($sql);
		}
		
		public function Insert($table, $data_values, $primaryKeyName)
		{
			$sql = "";
			$isFirst = true;
			$id = 0;
			$valuesClause = "";
		
			$sql = "INSERT INTO `".$table. "` (";
			foreach($data_values as $name => $value)
			{
				if($name != "" && !is_numeric($name))
				{
					if($name != $primaryKeyName)
					{
						if(!$isFirst)
						{
							$sql = $sql.", ";
							$valuesClause = $valuesClause.", ";
						}
						else
						{
							$isFirst = false;
						}
						
						$sql = $sql."`".$name."`";
						$valuesClause = $valuesClause."'".mysql_real_escape_string($value)."'";
					}
				}
			}
		
			$sql = $sql.") VALUES (".$valuesClause.")";
			
			if(mysql_query($sql))
				return mysql_insert_id();
				
			return 0;
		}
		
		public function Update($table, $data_values, $primaryKeyName, $primaryKeyValue)
		{
			$sql = "";
			$isFirst = true;
			
			$sql = "UPDATE `".$table."` SET ";
			
			foreach($data_values as $name => $value)
			{
				if($name != "" && !is_numeric($name))
				{
					if($name != $primaryKeyName)
					{
						if(!$isFirst)
						{
							$sql = $sql.", ";
						}
						else
						{
							$isFirst = false;
						}
						
						$sql = $sql."`".$name."`='".mysql_real_escape_string($value)."'";
					}
				}
			}
			
			$sql = $sql." WHERE `".$primaryKeyName."`=".$primaryKeyValue;
			
			return mysql_query($sql);
		}
		
		public function UpdateWhere($table, $data_values, $where, $length = 0)
		{
			$sql = "";
			$isFirst = true;
			
			$sql = "UPDATE `".$table."` SET ";
			
			foreach($data_values as $name => $value)
			{
				if($name != "" && !is_numeric($name))
				{
					if(!$isFirst)
					{
						$sql = $sql.", ";
					}
					else
					{
						$isFirst = false;
					}
					
					$sql = $sql."`".$name."`='".mysql_real_escape_string($value)."'";
				}
			}
			
			$sql = $sql." WHERE ".$where." ";
			
			if($length > 0)
				$sql = $sql."LIMIT ".$length;
			
			return mysql_query($sql);
		}
		
		public function Delete($table, $primaryKeyName, $primaryKeyValue)
		{
			$sql = "";
			
			$sql = "DELETE FROM `".$table."` WHERE `".$primaryKeyName."`='".$primaryKeyValue."'";
			
			return mysql_query($sql);
		}
		
		public function Query($table, $where, $sort = "", $join = "", $length = 0, $startIndex = 0)
		{
			$sql = "";
			$result = null;
			$numRows = 0;
			$rows = null;
			$row = null;
			
			$sql = "SELECT ";
			
			$sql = $sql."* FROM `".$table."` ";
			if($join != "")
				$sql = $sql.$join." ";
			$sql = $sql."WHERE ".$where." ";
			if($sort != "")
				$sql = $sql."ORDER BY ".$sort." ";		
		
			if($length > 0)
				$sql = $sql."LIMIT ".$startIndex.", ".$length;
			
			$result = mysql_query($sql);
			
			$rows = array();
			
			while($row = mysql_fetch_array($result))
			{
				$rows[] = $row;
			}
			
			return $rows;
		}
		
		public function QueryCount($table, $where, $join = "", $length = 0, $startIndex = 0)
		{
			$sql = "";
			$result = null;
			$row = null;
			
			$sql = "SELECT ";
			
			$sql = $sql."COUNT(*) FROM `".$table."` ";
			if($join != "")
				$sql = $sql.$join." ";
			$sql = $sql."WHERE ".$where." ";	
		
			if($length > 0)
				$sql = $sql."LIMIT ".$startIndex.", ".$length;
		
			$result = mysql_query($sql);
			
			$row = mysql_fetch_array($result);
			
			return $row[0];
		}
		
		public function CustomQuery($sql)
		{
			$result = null;
			$numRows = 0;
			$rows = null;
			$row = null;
		
			$result = mysql_query($sql);
			
			$rows = array();
			
			while($row = mysql_fetch_array($result))
			{
				$rows[] = $row;
			}
			
			return $rows;
		}
		
		public function CustomSQL($sql)
		{
			return mysql_query($sql);
		}
	}
?>