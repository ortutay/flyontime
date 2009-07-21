<?php

	abstract class ObjCollection extends DataCollection
	{
		public $_DataObj;
	
		function __construct()
		{
			parent::__construct();
			$this->_DataObj = $this->GetDataObj();
		}
	
		abstract public function GetDataObj();
		abstract public function CreateObj();
		
		public function InitFromRowsArray($rows)
		{
			$newObj = null;
			$success = false;
			
			$this->Clear();
			
			foreach($rows as $row)
			{
				$newObj = $this->CreateObj();
				$newObj->InitFromRowArray($row);
				
				$this->Add($newObj);
				
				$success = true;
			}

			return $success;
		}
		
		public function LoadAll($length = 0, $startIndex = 0)
		{
			return $this->InitFromRowsArray($this->_DataObj->LoadAll("", $length, $startIndex));
		}
		
		public function Save()
		{
			foreach($this->_Items as $obj)
			{
				$obj->Save();
			}
		}
		
		public function LockTable()
		{
			return $this->_DataObj->LockTable();
		}
		
		public function UnlockAllTables()
		{
			return $this->_DataObj->UnlockAllTables();
		}

	}

?>