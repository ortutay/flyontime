<?php

	class Tweets_DL extends DataObject
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetTableName()
		{
			return $GLOBALS["dbprefix"]."tweets";
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
		
		public function GetUsers($limit = -1)
		{
			$table = $this->GetTableName();
			
			$sql = "SELECT {$table}.username, COUNT({$table}.msgid) as numtweets FROM {$table} WHERE {$table}.matched=0 GROUP BY {$table}.username";
			
			if($limit > 0)
				$sql .= " LIMIT ".$limit;
			
			return $this->CustomLoad($sql);
		}
		
		public function LoadByUsernameByMatched($username, $matched = 0)
		{
			$table = $this->GetTableName();
			
			return $this->Load("{$table}.username='{$username}' AND {$table}.matched={$matched}", "{$table}.created ASC");
		}
		
		public function SetMatchedByUsernameByMatched($username, $matched_old = 0, $matched_new = 1)
		{
			$table = $this->GetTableName();
			
			$sql = "UPDATE {$table} set {$table}.matched={$matched_new} WHERE {$table}.username='{$username}' AND {$table}.matched={$matched_old}";
			
			return $this->CustomSQL($sql);
		}
	}

?>