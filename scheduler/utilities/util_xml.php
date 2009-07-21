<?php
		
	class util_xml
	{
		public static function EncodeValue($value)
		{
			$value_encoded = preg_replace("/[^a-zA-Z0-9\s\!\@\#\$\%\^\&\*\(\)\-\_\+\=\[\{\]\}\\\|\;\:\'\"\,\<\.\>\/\?]/", "", $value);
			$value_encoded = htmlentities($value_encoded);
			
			return $value_encoded;
		}
	}


?>