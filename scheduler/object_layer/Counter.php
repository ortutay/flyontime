<?php

	class Counter extends Obj
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new Counters_DL();
			return $do;
		}
		
		public function GetNewValuesArray()
		{
			return array("id" => 0, "countsincereset" => 0, "resetdate" => 0, "lastdate" => 0);
		}
	}
	
?>