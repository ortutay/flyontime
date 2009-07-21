<?php

	class TweetCollection extends ObjCollection
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new Tweets_DL();
			return $do;
		}
		
		public function CreateObj()
		{
			$o = new Tweet();
			return $o;
		}
		
		public function GetUsers($limit = -1)
		{
			return $this->InitFromRowsArray($this->_DataObj->GetUsers($limit));
		}
		
		public function LoadByUsernameByMatched($username, $matched = 0)
		{
			return $this->InitFromRowsArray($this->_DataObj->LoadByUsernameByMatched($username, $matched));
		}
		
		public function SetMatchedByUsernameByMatched($username, $matched_old = 0, $matched_new = 1)
		{
			return $this->_DataObj->SetMatchedByUsernameByMatched($username, $matched_old, $matched_new);
		}
		
		public function ReserveUnmatchedUserMsgs($username)
		{
			$success = false;
			
			while(!$this->LockTable())
			{
				sleep(1);
			}
			
			if($this->InitFromRowsArray($this->_DataObj->LoadByUsernameByMatched($username)))
			{
				$success = $this->SetMatchedByUsernameByMatched($username);
			}
			
			$this->UnlockAllTables();
			
			return $success;
		}
	}

?>