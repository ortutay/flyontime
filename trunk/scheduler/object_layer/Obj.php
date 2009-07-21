<?php

	abstract class Obj extends DataItem
	{
		public static $eNEW = 0;
		public static $eUNMODIFIED = 1;
		public static $eMODIFIED = 2;
		public static $eDELETED = 3;
	
		public $_DataObj;
		public $_State;

		function __construct()
		{	
			$this->_DataObj = $this->GetDataObj();
			$this->_State = self::$eNEW;
			
			parent::__construct();
		}
		
		abstract public function GetDataObj();
		
		public function SetValue($name, $value)
		{
			parent::SetValue($name, $value);
			$this->UpdateStateAfterPropertyChange();
		}
		
		public function GetPrimaryKeyName()
		{
			return $this->_DataObj->GetPrimaryKeyName();
		}
		
		public function InitFromRowArray($row)
		{
			$this->_Values = $row;
			$this->_State = self::$eUNMODIFIED;
		}
		
		public function InitFromRowsArray($rows)
		{
			$this->InitFromRowArray($rows[0]);
			
			if($this->GetPrimaryKey() > 0)
			{
				return true;
			}
				
			return false;
		}
		
		public function Save()
		{		
			$success = false;
			$newPrimaryKey = 0;
			
			switch ($this->_State)
			{
				case self::$eNEW:
					$newPrimaryKey = $this->_DataObj->Insert($this->_Values);
										
					if($newPrimaryKey > 0)
					{
						$success = true;
						$this->SetPrimaryKey($newPrimaryKey);
						$this->_State = self::$eUNMODIFIED;
					}
					break;
				case self::$eUNMODIFIED:
					$success = true;
					break;
				case self::$eMODIFIED:
					$success = $this->_DataObj->Update($this->_Values, $this->GetPrimaryKey());
					if($success)
						$this->_State = self::$eUNMODIFIED;
					break;
				case self::$eDELETED:
					$success = false;
					break;
			}
			
			return $success;
		}
		
		public function Delete()
		{
			$success = false;
			
			switch ($this->_State)
			{
				case self::$eNEW:
					$success = false;
					break;
				case self::$eUNMODIFIED:
					$success = $this->_DataObj->Remove($this->GetPrimaryKey());
					if($success)
						$this->_State = self::$eDELETED;
					break;
				case self::$eMODIFIED:
					$success = $this->_DataObj->Remove($this->GetPrimaryKey());
					if($success)
						$this->_State = self::$eDELETED;
					break;
				case self::$eDELETED:
					$success = true;
					break;
			}
			
			return $success;
		}
		
		public function UpdateStateAfterPropertyChange()
		{
			switch ($this->_State)
			{
				case self::$eNEW:
					break;
				case self::$eUNMODIFIED:
					$this->_State = self::$eMODIFIED;
					break;
				case self::$eMODIFIED:
					break;
				case self::$eDELETED:
					break;
			}
		}
		
		Public Function LoadByPrimaryKey($primaryKey)
		{
			if($primaryKey > 0)
			{
				return $this->InitFromRowsArray($this->_DataObj->LoadByPrimaryKey($primaryKey));
			}
			
			return false;
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