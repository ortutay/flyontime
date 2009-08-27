<?php
class AirlinesController extends AppController {
	var $name = 'Airlines';
	var $uses = array();
	var $components = array('RequestHandler');
	
	function index()
	{
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Ontime =& ClassRegistry::init('Ontime');
		
		$airlines_used = $this->Ontime->find('all',
			array(
				'fields' => array(
					'carrier'
				),
				'group' => array(
					'carrier'
				)
			)
		);
		
		$airlines_used_arr = array();
		
		foreach($airlines_used as $airline)
		{
			$airlines_used_arr[] = $airline['Ontime']['carrier'];
		}
		
		$airlines = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $airlines_used_arr
				),
				'order' => array(
					'Enum.description'
				)
			)
		);
		
		$this->set('Airlines', $airlines);
	}
	
	function view($UniqueCarrier = '')
	{
		$this->Ontime =& ClassRegistry::init('Ontime');
		$this->Enum =& ClassRegistry::init('Enum');
		
		if($UniqueCarrier == '')
			$this->redirect('/airlines');
		
		$airline_enum = $this->Enum->find('first',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $UniqueCarrier
				)
			)
		);
		
		$airline_stats = $this->Ontime->find('all',
			array(
				'fields' => array('firstdate', 'lastdate', 'count', 'pct_cancel', 'pct_ontime', 'pct_20mindelay', 'delay_median'),
				'conditions' => array(
					'origin' => '',
					'dest' => '',
					'carrier' => $UniqueCarrier,
					'flightnum' => '0',
					'dayofweek' => '0',
					'hour' => '',
					'holiday' => '',
					'condition' => 'all')
				)
		);
		
		$routes = $this->Ontime->find('all',
			array(
				'fields' => array(
					'origin',
					'dest',
					'sum(count) as count',
					'avg(pct_ontime) as pct_ontime',
				),
				'conditions' => array(
					'origin != ""',
					'dest != ""',
					'carrier' => $UniqueCarrier,
					'flightnum != 0',
					'dayofweek' => '0',
					'hour' => '',
					'holiday' => '',
					'condition' => 'all'
				),
				'group' => array(
					'origin',
					'dest'
				),
				'order' => array(
					'sum(count) DESC'
				),
				'limit' => 25
			)
		);
		
		$geocodes = $this->GetAirportGeocodes($routes, array('origin', 'dest'));
		
		$this->set('FullName', $airline_enum['Enum']['description']);
		$this->set('Stats', $airline_stats[0]['Ontime']);
		$this->set('Routes', $routes);
		$this->set('Geocodes', $geocodes);
	}
	
	private function GetAirportGeocodes($airports, $AirportValue)
	{
		$airport_codes = array();
		
		if(is_array($AirportValue))
		{
			foreach($AirportValue as $av)
			{
				foreach($airports as $airport)
				{
					$airport_codes[] = $airport['Ontime'][$av];
				}
			}
		}
		else
		{
			foreach($airports as $airport)
			{
				$airport_codes[] = $airport['Ontime'][$AirportValue];
			}
		}
		
		$coords = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'AIRPORTS_GEOCODE',
					'Enum.code' => $airport_codes
				)
			)
		);
		
		$geocodes = array();
		
		foreach($coords as $coord)
		{
			$coord_arr = explode(',', $coord['Enum']['description']);
			
			$Lng = $coord_arr[0];
			$Lat = $coord_arr[1];
			$code = $coord['Enum']['code'];
			
			$geocodes[$code] = array('Lat' => $Lat, 'Lng' => $Lng);
		}
		
		return $geocodes;
	}

}
?>