<?php

	class StaticDataCollection extends DataCollection
	{
		private $_LastPrimaryKey;
	
		function __construct()
		{
			parent::__construct();
			
			$this->_LastPrimaryKey = 1;
		}
		
		public function AddStaticItem($newvalues)
		{
			$newobj;
			
			if(count($newvalues) > 0)
			{
				$newobj = new StaticDataItem();
				$newobj->SetPrimaryKey($this->_LastPrimaryKey);
			
				foreach($newvalues as $name => $value)
				{
					$newobj->SetValue($name, $value);
				}
				
				if(parent::Add($newobj))
				{
					$this->_LastPrimaryKey++;
					return true;
				}
			}
			
			return false;
		}
		
	}

?>