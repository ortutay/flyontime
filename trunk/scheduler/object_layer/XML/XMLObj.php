<?php

	class XMLObj
	{
		private $_dom;

		function __construct(&$arr)
		{
			$this->_dom = new DOMDocument("1.0");
			$this->_dom->formatOutput = true;
			
			$this->ParseArray($arr, $this->_dom);
		}
		
		private function ParseArray(&$arr, &$parent_node)
		{
			foreach($arr as $name => $value)
			{
				if(is_array($value))
				{
					if(!is_int($name))
					{
						$child_node = $this->_dom->createElement($name);
						$parent_node->appendChild($child_node);
						$this->ParseArray($value, $child_node);
					}
					else
					{
						$this->ParseArray($value, $parent_node);
					}
				}
				else
				{
					if(!is_int($name))
					{
						$value_encoded = util_xml::EncodeValue($value);
						
						$child_node = $this->_dom->createElement($name, $value_encoded);
						$parent_node->appendChild($child_node);
					}
				}
			}
		}
		
		public function GetXMLString()
		{
			return $this->_dom->saveXML();
		}
		
		public function SaveXMLToFile($filename)
		{
			return $this->_dom->save($filename);
		}
	}

?>