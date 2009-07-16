<?php
class StatisticsController extends AppController {
	var $name = 'Statistics';
	var $uses = array();
	
	function index()
	{
		
	}
	
	function airports($query)
	{
		$this->Ontime =& ClassRegistry::init('Ontime');
		$this->Enum =& ClassRegistry::init('Enum');
		
		$num_airports = 50;
		
		$airports = null;
		
		switch($query)
		{
			case 'scheduleddepartures':
			case 'percentontimedepartures':
			case 'percentcancelleddepartures':
			case 'averagedeparturedelay':
			case 'unluckydeparturedelay':

				switch($query)
				{
					case 'scheduleddepartures':
						$num_airports = 200;
						$field = 'count';
						$this->set('Name', 'Number of Departures');
						$this->set('DataTitle', 'Departures');
						break;
					case 'percentontimedepartures':
						$field = 'pct_ontime';
						$this->set('Name', 'On-Time Flights By Origin');
						$this->set('DataTitle', 'Percent On-Time');
						break;
					case 'percentcancelleddepartures':
						$field = 'pct_cancel';
						$this->set('Name', 'Cancelled/Diverted Flights By Origin');
						$this->set('DataTitle', 'Percent Cancelled or Diverted');
						break;
					case 'averagedeparturedelay':
						$field = 'delay_median';
						$this->set('Name', 'Median Arrival Delay by Origin');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
					case 'unluckydeparturedelay':
						$field = 'delay_85thpctile';
						$this->set('Name', '85th Percentile Arrival Delay by Origin');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
				}
				
				$airports = $this->Ontime->find('all',
					array(
						'fields' => array(
							'origin',
							$field,
						),
						'conditions' => array(
							'dest' => '',
							'carrier' => '',
							'flightnum' => 0,
							'dayofweek' => 0,
							'hour' => '',
							'holiday' => '',
							'condition' => 'all',
						),
						'order' => array(
							'count DESC'
						),
						'limit' => $num_airports
					)
				);
				
				$this->set('DataValue', $field);
				$AirportValue = 'origin';
				
				break;
				
			case 'scheduledarrivals':
			case 'percentontimearrivals':
			case 'percentcancelledarrivals':
			case 'averagearrivaldelay':
			case 'unluckyarrivaldelay':

				switch($query)
				{
					case 'scheduledarrivals':
						$num_airports = 200;
						$field = 'count';
						$this->set('Name', 'Number of Arrivals');
						$this->set('DataTitle', 'Arrivals');
						break;
					case 'percentontimearrivals':
						$field = 'pct_ontime';
						$this->set('Name', 'On-Time Flights By Destination');
						$this->set('DataTitle', 'Percent On-Time');
						break;
					case 'percentcancelledarrivals':
						$field = 'pct_cancel';
						$this->set('Name', 'Cancelled/Diverted Flights By Destination');
						$this->set('DataTitle', 'Percent Cancelled or Diverted');
						break;
					case 'averagearrivaldelay':
						$field = 'delay_median';
						$this->set('Name', 'Median Arrival Delay by Desination');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
					case 'unluckyarrivaldelay':
						$field = 'delay_85thpctile';
						$this->set('Name', '85th Percentile Arrival Delay by Desination');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
				}
				
				$airports = $this->Ontime->find('all',
					array(
						'fields' => array(
							'dest',
							$field,
						),
						'conditions' => array(
							'origin' => '',
							'carrier' => '',
							'flightnum' => 0,
							'dayofweek' => 0,
							'hour' => '',
							'holiday' => '',
							'condition' => 'all',
						),
						'order' => array(
							'count DESC'
						),
						'limit' => $num_airports
					)
				);
				
				$this->set('DataValue', $field);
				$AirportValue = 'dest';
				
				break;
				
			default:
			
				$this->redirect('/statistics');
		}
		
		$geocodes = $this->GetAirportGeocodes($airports, $AirportValue);
		$airport_names = $this->GetAirportNames($airports, $AirportValue);
		
		$this->set('AirportValue', $AirportValue);
		$this->set('Airports', $airports);
		$this->set('AirportNames', $airport_names);
		$this->set('Geocodes', $geocodes);
	}
	
	
	function airlines($query)
	{
		$this->Ontime =& ClassRegistry::init('Ontime');
		$this->Enum =& ClassRegistry::init('Enum');
		
		$airlines = null;
		$airline_names = null;
		
		switch($query)
		{
			case 'scheduledflights':
			case 'percentontime':
			case 'percentcancelled':
			case 'averagearrivaldelay':
			case 'unluckyarrivaldelay':
				
				$orderby = 'x';
				
				switch($query)
				{
					case 'scheduledflights':
						$field = '(count*1)';
						$this->set('Name', 'Number of Flights');
						$this->set('DataTitle', 'Flights');
						break;
					case 'percentontime':
						$field = '(100*pct_ontime)';
						$this->set('Name', 'Percent of Flights Arriving On Time (w/in 5 Minutes)');
						$this->set('DataTitle', '% On-Time');
						break;
					case 'percentcancelled':
						$field = '(100*pct_cancel)';
						$this->set('Name', 'Percent of Flights Cancelled/Diverted');
						$this->set('DataTitle', '% Cancelled/Diverted');
						break;
					case 'averagearrivaldelay':
						$orderby = 'count';
						$field = '(delay_median*1)';
						$this->set('Name', 'Median Arrival Delay');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
					case 'unluckyarrivaldelay':
						$orderby = 'count';
						$field = '(delay_85thpctile*1)';
						$this->set('Name', '85th Percentile Arrival Delay');
						$this->set('DataTitle', 'Delay (minutes)');
						break;
				}
				
				$airlines = $this->Ontime->find('all',
					array(
						'fields' => array(
							'carrier',
							$field . ' as x',
						),
						'conditions' => array(
							'origin' => '',
							'dest' => '',
							'flightnum' => 0,
							'dayofweek' => 0,
							'hour' => '',
							'holiday' => '',
							'condition' => 'all',
						),
						'order' => array(
							$orderby . ' DESC'
						),
						'limit' => 20
					)
				);

				$this->set('DataValue', 'x');
				
				break;
				
			default:
			
				$this->redirect('/statistics');
		}
		
		$airline_names = $this->GetAirlineNames($airlines);
		
		$this->set('Airlines', $airlines);
		$this->set('AirlineNames', $airline_names);
	}
	
	private function GetAirlineNames($airlines)
	{
		$unique_carriers = array();
		
		foreach($airlines as $airline)
		{
			$unique_carriers[] = $airline['Ontime']['carrier'];
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
	
	private function GetAirportNames($airports, $AirportValue)
	{
		$airport_codes = array();
		
		foreach($airports as $airport)
		{
			$airport_codes[] = $airport['Ontime'][$AirportValue];
		}
		
		$result = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'AIRPORTS',
					'Enum.code' => $airport_codes
				)
			)
		);
		
		$airport_names = array();
		
		foreach($result as $item)
		{
			$airport_names[$item['Enum']['code']] = $item['Enum']['description'];
		}
		
		return $airport_names;
	}

}
?>