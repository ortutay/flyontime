<?php

	class Line extends Obj
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new Lines_DL();
			return $do;
		}
		
		public function GetNewValuesArray()
		{
			return array("id" => 0, "userhash" => "", "linetype" => "", "airportcode" => "", "airlinecode" => "", "linename" => "", "in" => "0000-00-00 00:00:00", "out" => "0000-00-00 00:00:00", "diff" => 0, "inyear" => 0, "inmonth" => 0, "indayofmonth" => 0, "indayofweek" => 0, "intimeblk15" => "", "intimeblk30" => "", "intimeblk60" => "", "source" => "", "useragent" => "", "timezone" => "");
		}
	}
	
?>