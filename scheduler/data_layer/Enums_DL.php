<?php

	class Enums_DL extends DataObject
	{
		function __construct()
		{
			parent::__construct();
		}
	
		public function GetTableName()
		{
			return $GLOBALS["dbprefix"]."enums";
		}
		
		public function GetPrimaryKeyName()
		{
			return "id";
		}
		
		public function LoadByCategoryByCode($category, $code)
		{
			$table = $this->GetTableName();
			
			return $this->Load("{$table}.category='{$category}' AND {$table}.code='{$code}'");
		}
	}

?>