<?php

	class Enum extends Obj
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
		
		public function GetNewValuesArray()
		{
			return array("id" => 0, "code" => "", "description" => "", "category" => "");
		}
		
		public function LoadByCategoryByCode($category, $code)
		{
			return $this->InitFromRowsArray($this->_DataObj->LoadByCategoryByCode($category, $code));
		}
	}
	
?>