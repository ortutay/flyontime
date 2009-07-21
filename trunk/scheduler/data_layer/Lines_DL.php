<?php

	class Lines_DL extends DataObject
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetTableName()
		{
			return $GLOBALS["dbprefix"]."lines";
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
	}

?>