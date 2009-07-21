<?php

	class ScheduledTasks_DL extends DataObject
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetTableName()
		{
			return $GLOBALS["dbprefix"]."tasks";
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
		
		public function LoadExpiredTasks($length = 0)
		{
			$where = "";
			$where .= ($this->GetTableName()).".Expires<='".date("Y-m-d G:i:s")."'";
			$where .= " AND ".($this->GetTableName()).".Completed=0";
			$where .= " AND ".($this->GetTableName()).".InProgress=0";
			
			return $this->Load($where, ($this->GetTableName()).".Expires ASC", "", $length);
		}
		
		public function RemoveCompletedTasks()
		{
			$sql = "DELETE FROM ".($this->GetTableName())." WHERE ".($this->GetTableName()).".Completed=1";
			
			return $this->CustomSQL($sql);
		}
		
		public function ResetInProgressTasksStartedBefore($LatestProgressStart)
		{
			$table = $this->GetTableName();
			
			$sql = "UPDATE {$table} set {$table}.InProgress=0, {$table}.ProgressStart='0000-00-00 00:00:00' WHERE {$table}.ProgressStart<='{$LatestProgressStart}' AND {$table}.InProgress=1 AND {$table}.Completed=0";
			
			return $this->CustomSQL($sql);
		}
	}

?>