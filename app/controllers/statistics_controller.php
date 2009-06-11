<?php
class StatisticsController extends AppController {
	var $name = 'Statistics';
	var $uses = array();
	
	function index()
	{
		
	}
	
	function airports($query)
	{
		$this->Log =& ClassRegistry::init('Log');
		
		$airports = null;
		
		switch($query)
		{
			case 'departuredelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.DepDelayMinutes) as TotalMinutesDelayed'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'TotalMinutesDelayed DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Departure Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalMinutesDelayed');
				
				break;
			
			case 'cancelledflights':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'COUNT(Log.Cancelled) as NumCancelledFlights'
						),
						'conditions' => array(
							'Log.Cancelled' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'NumCancelledFlights DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Cancelled Flights');
				$this->set('DataTitle', 'Num Cancelled Flights');
				$this->set('DataValue', 'NumCancelledFlights');
				
				break;
			
			case 'carrierdelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.CarrierDelay) as TotalCarrierDelay'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'TotalCarrierDelay DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Carrier Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalCarrierDelay');
				
				break;
			
			case 'weatherdelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.WeatherDelay) as TotalWeatherDelay'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'TotalWeatherDelay DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Weather Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalWeatherDelay');
				
			case 'nasdelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.NASDelay) as TotalNASDelay'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'TotalNASDelay DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport NAS Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalNASDelay');
				
				break;
			
			case 'securitydelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.SecurityDelay) as TotalSecurityDelay'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'TotalSecurityDelay DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Security Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalSecurityDelay');
				
				break;
			
			case 'lateaircraftdelays':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Origin',
							'SUM(Log.LateAircraftDelay) as TotalLateAircraftDelay'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.DepDel15' => 1
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'LateAircraftDelay DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Late Aircraft Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalLateAircraftDelay');
				
				break;
				
			default:
			
				$this->redirect('/statistics');
		}
		
		$this->set('Airports', $airports);
	}
	
	
	function airlines($query)
	{
		$this->Log =& ClassRegistry::init('Log');
		$this->Enum =& ClassRegistry::init('Enum');
		
		$airlines = null;
		$airline_names = null;
		
		switch($query)
		{
			case 'departuredelays':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.UniqueCarrier',
							'SUM(Log.DepDelay) as TotalMinutesDelayed'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'TotalMinutesDelayed DESC'
						),
						'limit' => 20
					)
				);
				
				$airline_names = $this->GetAirlineNames($airlines);
				
				$this->set('Name', 'Airline Departure Delays');
				$this->set('DataTitle', 'Minutes Delayed');
				$this->set('DataValue', 'TotalMinutesDelayed');
				
				break;
			
			case 'cancelledflights':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.UniqueCarrier',
							'COUNT(Log.Cancelled) as NumCancelledFlights'
						),
						'conditions' => array(
							'Log.Cancelled' => 0
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'NumCancelledFlights DESC'
						),
						'limit' => 20
					)
				);
				
				$airline_names = $this->GetAirlineNames($airlines);
				
				$this->set('Name', 'Airline Cancelled Flights');
				$this->set('DataTitle', 'Cancelled Flights');
				$this->set('DataValue', 'NumCancelledFlights');
				
				break;
				
			default:
			
				$this->redirect('/statistics');
		}
		
		$this->set('Airlines', $airlines);
		$this->set('AirlineNames', $airline_names);
	}
	
	private function GetAirlineNames($airlines)
	{
		$unique_carriers = array();
		
		foreach($airlines as $airline)
		{
			$unique_carriers[] = $airline['Log']['UniqueCarrier'];
		}
		
		$result = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $unique_carriers
				)
			)
		);
		
		$airline_names = array();
		
		foreach($result as $item)
		{
			$airline_names[$item['Enum']['code']] = $item['Enum']['description'];
		}
		
		return $airline_names;
	}

}
?>