<?php
class AirlinesController extends AppController {
	var $name = 'Airlines';
	var $uses = array();
	
	function index()
	{
		//$this->Enum =& ClassRegistry::init('Enum');
		//$this->Log =& ClassRegistry::init('Log');
	}
	
	function view($UniqueCarrier = '')
	{
		$this->Enum =& ClassRegistry::init('Enum');
		
		$airline_enum = $this->Enum->find('first',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $UniqueCarrier
				)
			)
		);
		
		$this->set('FullName', $airline_enum['Enum']['description']);
	}

}
?>