<?php

	class ScheduledTaskCollection extends ObjCollection
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new ScheduledTasks_DL();
			return $do;
		}
		
		public function CreateObj()
		{
			$o = new ScheduledTask();
			return $o;
		}
		
		public function RemoveCompletedTasks()
		{
			return $this->_DataObj->RemoveCompletedTasks();
		}
		
		public function ResetInProgressTasksStartedBefore($LatestProgressStart)
		{
			return $this->_DataObj->ResetInProgressTasksStartedBefore($LatestProgressStart);
		}
	}

?>