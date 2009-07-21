<?php

	class EnumCollection extends ObjCollection
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetDataObj()
		{
			$do = new Enums_DL();
			return $do;
		}
		
		public function CreateObj()
		{
			$o = new Enum();
			return $o;
		}
	}

?>