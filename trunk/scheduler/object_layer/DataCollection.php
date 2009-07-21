<?php

	class DataCollection
	{
		protected $_Items;
	
		function __construct()
		{
			$this->_Items = array();
		}
		
		public function Add($newObj)
		{
			if($newObj != null)
			{
				//HACKAGE: we may want to require this in the future
				//if($newObj->GetPrimaryKey() > 0)
				{
					array_push($this->_Items, $newObj);
					return true;
				}
			}
			
			return false;
		}
		
		public function Count()
		{
			return count($this->_Items);
		}
		
		public function Clear()
		{
			$this->_Items = array();
		}
		
		public function Remove($primaryKey)
		{
			$c = $this->Count();
			$obj = null;
			
			for($i = 0; $i < $c; $i++)
			{
				$obj = array_shift($this->_Items);
				if($obj->GetPrimaryKey() == $primaryKey)
				{
					return $obj;
				}
				else
				{
					array_push($this->_Items, $obj);
				}
			}
			
			return null;
		}
		
		public function Items()
		{
			return $this->_Items;
		}
		
		public function Item($index)
		{
			return $this->_Items[$index];
		}
	}

?>