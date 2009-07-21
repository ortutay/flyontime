<?php

	abstract class DataObject
	{
		public static $eINTAUTOINC = 1;
		public static $eGUID = 2;
		public static $eCUSTOM = 3;
		
		public $_conn;
		
		function __construct()
		{
			$this->_conn = new DataConnector();
		}
	
		abstract public function GetTableName();
		abstract public function GetPrimaryKeyName();
		
		public function GetPrimaryKeyType()
		{
			return self::$eINTAUTOINC;
		}
		
		public function LockTable()
		{
			return $this->_conn->LockTable($this->GetTableName());
		}
		
		public function UnlockAllTables()
		{
			return $this->_conn->UnlockAllTables();
		}
		
		public function Insert($data_values)
		{
			return $this->_conn->Insert($this->GetTableName(), $data_values, $this->GetPrimaryKeyName(), $this->GetPrimaryKeyType());
		}
		
		public function Update($data_values, $primaryKeyValue)
		{
			return $this->_conn->Update($this->GetTableName(), $data_values, $this->GetPrimaryKeyName(), $primaryKeyValue, $this->GetPrimaryKeyType());
		}
		
		public function UpdateWhere($data_values, $where, $length = 0)
		{
			return $this->_conn->UpdateWhere($this->GetTableName(), $data_values, $where, $length);
		}
		
		public function Remove($primaryKeyValue)
		{
			return $this->_conn->Delete($this->GetTableName(), $this->GetPrimaryKeyName(), $primaryKeyValue);
		}
		
		protected function Load($where, $sort = "", $join = "", $length = 0, $startIndex = 0)
		{
			return $this->_conn->Query($this->GetTableName(), $where, $sort, $join, $length, $startIndex);
		}
		
		protected function LoadCount($where, $join = "", $length = 0, $startIndex = 0)
		{
			return $this->_conn->QueryCount($this->GetTableName(), $where, $join, $length, $startIndex);
		}
		
		public function LoadAll($sort = "", $length = 0, $startIndex = 0)
		{
			return $this->Load("1=1", $sort, "", $length, $startIndex);
		}
		
		public function LoadByPrimaryKey($primaryKeyValue)
		{
			if($this->GetPrimaryKeyType() == self::$eCUSTOM)
			{
				return $this->Load(($this->GetTableName()).".".($this->GetPrimaryKeyName())."='".$primaryKeyValue."'");
			}
			
			return $this->Load(($this->GetTableName()).".".($this->GetPrimaryKeyName())."=".$primaryKeyValue);
		}
		
		public function CustomLoad($sql)
		{
			return $this->_conn->CustomQuery($sql);
		}
		
		public function CustomSQL($sql)
		{
			return $this->_conn->CustomSQL($sql);
		}
	}

?>