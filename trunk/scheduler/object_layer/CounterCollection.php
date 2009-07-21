<?php

	class CounterCollection extends ObjCollection
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
		
		public function CreateObj()
		{
			$o = new Counter();
			return $o;
		}
	}

?>