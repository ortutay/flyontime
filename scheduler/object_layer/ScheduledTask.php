<?php

	class ScheduledTask extends Obj
	{
		public static $eFETCHTWITTERRESULTS = 1;
		public static $ePARSETWITTERRESULTS = 2;
		public static $eMATCHTWEETS = 3;
		public static $eFETCHTWITTERMYTSARESULTS = 4;
		
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new ScheduledTasks_DL();
			return $do;
		}
		
		public function GetNewValuesArray()
		{
			return array("id" => 0, "Expires" => "0000-00-00 00:00:00", "HandlerNumber" => 0, "Completed" => 0, "InProgress" => 0, "ProgressStart" => "0000-00-00 00:00:00", "Params" => "");
		}
		
		public function LoadExpiredTask()
		{
			$success = false;
			
			while(!$this->LockTable())
			{
				sleep(1);
			}
			
			if($this->InitFromRowsArray($this->_DataObj->LoadExpiredTasks(1)))
			{
				$this->SetValue("InProgress", 1);
				$this->SetValue("ProgressStart", date("Y-m-d G:i:s"));
				$success = $this->Save();
			}
			
			$this->UnlockAllTables();
			
			return $success;
		}
		
		public function MarkAsCompleted()
		{
			$this->SetValue("InProgress", 0);
			$this->SetValue("ProgressStart", "0000-00-00 00:00:00");
			$this->SetValue("Completed", 1);
			
			return $this->Save();
		}
		
		public function AdvanceExpirationBy($numSeconds)
		{
			$this->SetValue("InProgress", 0);
			$this->SetValue("ProgressStart", "0000-00-00 00:00:00");
			$this->SetValue("Completed", 0);
			$this->SetValue("Expires", date("Y-m-d G:i:s", time() + $numSeconds));
			
			return $this->Save();
		}
	}
	
?>
