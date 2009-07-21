<?php

	class Counters_DL extends DataObject
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetTableName()
		{
			return $GLOBALS["dbprefix"]."counters";
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
		
		public function GetPrimaryKeyType()
		{
			return self::$eCUSTOM;
		}
	}

?>