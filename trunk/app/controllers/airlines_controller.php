<?php
class AirlinesController extends AppController {
	var $name = 'Airlines';
	var $uses = array();
	
	function index()
	{
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Log =& ClassRegistry::init('Log');
		
		$airlines_used = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.UniqueCarrier'
				),
				'group' => array(
					'Log.UniqueCarrier'
				)
			)
		);
		
		$airlines_used_arr = array();
		
		foreach($airlines_used as $airline)
		{
			$airlines_used_arr[] = $airline['Log']['UniqueCarrier'];
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
		$this->Log =& ClassRegistry::init('Log');
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
		
		$airline_stats = $this->Log->find('all',
			array(
				'fields' => array(
					'COUNT(UniqueCarrier) as NumScheduled',
					'SUM(Cancelled) as NumCancelled',
					'SUM(Diverted) as NumDiverted',
					'SUM(ArrDel15) as NumDelayed',
					'SUM(ArrDelay) as TotalArrivalDelay'
				),
				'conditions' => array(
					'Log.UniqueCarrier' => $UniqueCarrier
				)
			)
		);
		
		$routes = $this->Log->find('all',
			array(
				'fields' => array(
					'Origin',
					'OriginCityName',
					'Dest',
					'DestCityName',
					'Month',
					'Year',
					'COUNT(UniqueCarrier) as NumScheduled'
				),
				'conditions' => array(
					'Log.UniqueCarrier' => $UniqueCarrier
				),
				'group' => array(
					'Origin',
					'Dest'
				),
				'order' => array(
					'NumScheduled DESC'
				),
				'limit' => 150
			)
		);
		
		$months = $this->GetMonths($routes);
		$geocodes = $this->GetAirportGeocodes($routes, array('Origin', 'Dest'));
		
		$this->set('FullName', $airline_enum['Enum']['description']);
		$this->set('Stats', $airline_stats[0][0]);
		$this->set('Routes', $routes);
		$this->set('Months', $months);
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
					$airport_codes[] = $airport['Log'][$av];
				}
			}
		}
		else
		{
			foreach($airports as $airport)
			{
				$airport_codes[] = $airport['Log'][$AirportValue];
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
	
	private function GetMonths($flights)
	{
		$months = array();
			
		foreach($flights as $flight)
		{
			$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
			$date_str = date('F, Y', strtotime($date));
			$months[$date_str] = '';
		}
		
		return $months;
	}

}
?>