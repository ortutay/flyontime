<?php

	class Tweet extends Obj
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
		
		public function GetNewValuesArray()
		{
			return array("id" => 0, "msgid" => "", "username" => "", "created" => 0, "msg" => "", "matched" => 0);
		}
	}
	
?>
