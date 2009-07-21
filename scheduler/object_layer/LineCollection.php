<?php

	class LineCollection extends ObjCollection
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
		
		public function CreateObj()
		{
			$o = new Line();
			return $o;
		}
	}

?>