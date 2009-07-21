<?php
	
	abstract class DataItem
	{
		protected $_Values;

		function __construct()
		{			
			$this->_Values = $this->GetNewValuesArray();
			$this->SetPrimaryKey(0);
		}
		
		abstract public function GetNewValuesArray();
		abstract public function GetPrimaryKeyName();
		
		public function GetValue($name)
		{
			return $this->_Values[$name];
		}
		
		public function SetValue($name, $value)
		{
			$this->_Values[$name] = $value;
		}
		
		public function ClearValues()
		{
			$this->_Values = $this->GetNewValuesArray();
		}
		
		public function GetPrimaryKey()
		{
			return $this->GetValue($this->GetPrimaryKeyName());
		}
		
		public function SetPrimaryKey($value)
		{
			$this->SetValue($this->GetPrimaryKeyName(), $value);
		}
		
	}

?>