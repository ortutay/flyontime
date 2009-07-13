<?php
class DisambiguateComponent extends Object {

	//==========START BOILER PLATE CODE================

	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
		
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Log =& ClassRegistry::init('Log');
	}

	//called after Controller::beforeFilter()
	function startup(&$controller) {
	}

	//called after Controller::beforeRender()
	function beforeRender(&$controller) {
	}

	//called after Controller::render()
	function shutdown(&$controller) {
	}

	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}

	function redirectSomewhere($value) {
		// utilizing a controller method
		$this->controller->redirect($value);
	}
	//==========END BOILER PLATE CODE================


	public function GetAirports($name, $airports_used)
	{
		$airports = array();
		
		if(strlen($name) == 3)
		{
			$airports = $this->GetAirportsByCode($name, $airports_used);
			
			if(count($airports) == 0)
			{
				$airports = $this->GetAirportsByKeywords($name, $airports_used);
			}
		}
		elseif($name != "")
		{
			$airports = $this->GetAirportsByKeywords($name, $airports_used);
		}
		
		return $airports;
	}
	
	private function GetAirportsByCode($name, $airports_used)
	{
		if($this->IsAirportUsed($name, $airports_used))
		{
			$airports = $this->Enum->find('all',
				array(
					'conditions' => array(
						'Enum.category' => 'AIRPORTS',
						'Enum.code' => strtoupper($name)
					)
				)
			);
			
			return $airports;
		}
		
		return array();
	}
	
	private function IsAirportUsed($name, $airports_used)
	{
		$name_upper = strtoupper($name);
		
		foreach($airports_used as $code)
		{
			if($name_upper == $code)
				return true;
		}
		
		return false;
	}
	
	private function GetAirportsByKeywords($name, $airports_used)
	{
		$keywords = $this->ParseCityName($name);
			
		$like = '';
		foreach($keywords as $keyword)
		{
			$like .= '%'.$keyword;
		}
		$like .= '%';
		
		$conditions = array(
			'Enum.category' => 'AIRPORTS',
			'Enum.description LIKE' => $like
		);
		
		if($airports_used != null)
		{
			$conditions['Enum.code'] = $airports_used;
		}
		
		$airports = $this->Enum->find('all',
			array(
				'conditions' => $conditions
			)
		);
		
		return $airports;
	}
	
	private function ParseCityName($name)
	{
		$parts = explode(' ', $name);
		
		$keywords = array();
		
		foreach($parts as $part)
		{
			$keyword = preg_replace("/[^a-zA-Z]/", '', $part);
			
			if($keyword != '')
				$keywords[] = $keyword;
		}
		
		return $keywords;
	}
	
	public function GetAirportsUsed()
	{
		$airports_used = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.Origin'
				),
				'group' => array(
					'Log.Origin'
				)
			)
		);
		
		$airports_used_arr = array();
		
		foreach($airports_used as $airport)
		{
			$airports_used_arr[] = $airport['Log']['Origin'];
		}
		
		return $airports_used_arr;
	}
	
}
?>