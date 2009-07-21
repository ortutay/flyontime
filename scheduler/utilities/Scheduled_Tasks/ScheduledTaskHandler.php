<?php
	
	abstract class ScheduledTaskHandler
	{	
		function __construct()
		{
			
		}
		
		abstract public function Execute($aoScheduledTask);
		
		public function UnserializeParams($params)
		{
			if($params == "")
				return array();
				
			return unserialize(base64_decode($params));
		}
		
		public function SerializeParams($params)
		{
			if(!is_array($params))
				$params = array();
				
			return base64_encode(serialize($params));
		}
	
	}
	
?>