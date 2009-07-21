<?php

	class StaticDataItem extends DataItem
	{
		function __construct()
		{			
			parent::__construct();
		}
		
		public function GetNewValuesArray()
		{
			return array("id" => 0);
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
		
	}

?>