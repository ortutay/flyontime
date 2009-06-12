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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
							'Log.Month',
							'Log.Year',
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
		
		$months = $this->GetMonths($airports);
		
		$this->set('Airports', $airports);
		$this->set('Months', $months);
	}
	
	
	function airlines($query)
	{
		$this->Log =& ClassRegistry::init('Log');
		$this->Enum =& ClassRegistry::init('Enum');
		
		$airlines = null;
		$airline_names = null;
		
		switch($query)
		{
			case 'percentontime':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.UniqueCarrier',
							'((1-((SUM(Log.ArrDel15) + SUM(Log.Cancelled) + SUM(Log.Diverted))/COUNT(Log.UniqueCarrier)))*100) as PercentOnTime'
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'PercentOnTime DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Flights Arriving On Time');
				$this->set('DataTitle', 'Percent On-Time');
				$this->set('DataValue', 'PercentOnTime');
				
				break;
				
			case 'percentcancelled':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.UniqueCarrier',
							'((SUM(Log.Cancelled)/COUNT(Log.UniqueCarrier))*100) as PercentCancelled'
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'PercentCancelled DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Flights Cancelled');
				$this->set('DataTitle', 'Percent Cancelled');
				$this->set('DataValue', 'PercentCancelled');
				
				break;
			
			case 'percentdiverted':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.UniqueCarrier',
							'((SUM(Log.Diverted)/COUNT(Log.UniqueCarrier))*100) as PercentDiverted'
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'PercentDiverted DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Flights Diverted');
				$this->set('DataTitle', 'Percent Diverted');
				$this->set('DataValue', 'PercentDiverted');
				
				break;
				
			case 'averagearrivaldelay':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.UniqueCarrier',
							'AVG(Log.ArrDelay) as AverageMinutesDelayed'
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'AverageMinutesDelayed DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Average Minutes Arriving Late');
				$this->set('DataTitle', 'Average Minutes Late');
				$this->set('DataValue', 'AverageMinutesDelayed');
				
				break;
				
			case 'scheduledflights':
				
				$airlines = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.UniqueCarrier',
							'COUNT(Log.UniqueCarrier) as ScheduledFlights'
						),
						'group' => array(
							'Log.UniqueCarrier'
						),
						'order' => array(
							'ScheduledFlights DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Number of Scheduled Flights');
				$this->set('DataTitle', 'Scheduled Flights');
				$this->set('DataValue', 'ScheduledFlights');
				
				break;
				
			default:
			
				$this->redirect('/statistics');
		}
		
		$airline_names = $this->GetAirlineNames($airlines);
		$months = $this->GetMonths($airlines);
		
		$this->set('Airlines', $airlines);
		$this->set('AirlineNames', $airline_names);
		$this->set('Months', $months);
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