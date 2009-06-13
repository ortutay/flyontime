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
			case 'scheduleddepartures':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Origin',
							'COUNT(Log.Origin) as ScheduledDepartures'
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'ScheduledDepartures DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Scheduled Departures');
				$this->set('DataTitle', 'Scheduled Departures');
				$this->set('DataValue', 'ScheduledDepartures');
				$this->set('AirportValue', 'Origin');
				
				break;
				
			case 'scheduledarrivals':
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Dest',
							'COUNT(Log.Dest) as ScheduledArrivals'
						),
						'group' => array(
							'Log.Dest'
						),
						'order' => array(
							'ScheduledArrivals DESC'
						),
						'limit' => 20
					)
				);
				
				$this->set('Name', 'Airport Scheduled Arrivals');
				$this->set('DataTitle', 'Scheduled Arrivals');
				$this->set('DataValue', 'ScheduledArrivals');
				$this->set('AirportValue', 'Dest');
				
				break;
				
			case 'percentontimedepartures':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Origin',
							'((1-((SUM(Log.DepDel15) + SUM(Log.Cancelled))/COUNT(Log.Origin)))*100) as PercentOnTimeDepartures'
						),
						'conditions' => array(
							'Log.Origin' => $major_airports
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'PercentOnTimeDepartures DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Flights Departing On Time');
				$this->set('DataTitle', '% On-Time Departures');
				$this->set('DataValue', 'PercentOnTimeDepartures');
				$this->set('AirportValue', 'Origin');
				
				break;
				
			case 'percentontimearrivals':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Dest',
							'((1-((SUM(Log.ArrDel15) + SUM(Log.Cancelled) + SUM(Log.Diverted))/COUNT(Log.Dest)))*100) as PercentOnTimeArrivals'
						),
						'conditions' => array(
							'Log.Dest' => $major_airports
						),
						'group' => array(
							'Log.Dest'
						),
						'order' => array(
							'PercentOnTimeArrivals DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Flights Arriving On Time');
				$this->set('DataTitle', '% On-Time Arrivals');
				$this->set('DataValue', 'PercentOnTimeArrivals');
				$this->set('AirportValue', 'Dest');
				
				break;
			
			case 'percentcancelleddepartures':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Origin',
							'((SUM(Log.Cancelled)/COUNT(Log.UniqueCarrier))*100) as PercentCancelledDepartures'
						),
						'conditions' => array(
							'Log.Origin' => $major_airports
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'PercentCancelledDepartures DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Departing Flights Cancelled');
				$this->set('DataTitle', '% Cancelled Departures');
				$this->set('DataValue', 'PercentCancelledDepartures');
				$this->set('AirportValue', 'Origin');
				
				break;
			
			case 'percentcancelledarrivals':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Dest',
							'((SUM(Log.Cancelled)/COUNT(Log.UniqueCarrier))*100) as PercentCancelledArrivals'
						),
						'conditions' => array(
							'Log.Dest' => $major_airports
						),
						'group' => array(
							'Log.Dest'
						),
						'order' => array(
							'PercentCancelledArrivals DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Percent of Arriving Flights Cancelled');
				$this->set('DataTitle', '% Cancelled Arrivals');
				$this->set('DataValue', 'PercentCancelledArrivals');
				$this->set('AirportValue', 'Dest');
				
				break;
			
			case 'averagedeparturedelay':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Origin',
							'AVG(Log.DepDelay) as AverageMinutesDelayed'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.Origin' => $major_airports
						),
						'group' => array(
							'Log.Origin'
						),
						'order' => array(
							'AverageMinutesDelayed DESC'
						),
						'limit' => 20
					)
				);

				$this->set('Name', 'Average Minutes Departing Late');
				$this->set('DataTitle', 'Average Minutes Late');
				$this->set('DataValue', 'AverageMinutesDelayed');
				$this->set('AirportValue', 'Origin');
				
				break;
			
			case 'averagearrivaldelay':
				
				$major_airports = $this->GetMajorAirports(20);
				
				$airports = $this->Log->find('all',
					array(
						'fields' => array(
							'Log.Month',
							'Log.Year',
							'Log.Dest',
							'AVG(Log.ArrDelay) as AverageMinutesDelayed'
						),
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0,
							'Log.Dest' => $major_airports
						),
						'group' => array(
							'Log.Dest'
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
				$this->set('AirportValue', 'Dest');
				
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
				$this->set('DataTitle', '% On-Time');
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
				$this->set('DataTitle', '% Cancelled');
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
				$this->set('DataTitle', '% Diverted');
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
						'conditions' => array(
							'Log.Cancelled' => 0,
							'Log.Diverted' => 0
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
	
	private function GetMajorAirports($top)
	{
		$airports = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.Origin',
					'COUNT(Log.Origin) as ScheduledDepartures'
				),
				'group' => array(
					'Log.Origin'
				),
				'order' => array(
					'ScheduledDepartures DESC'
				),
				'limit' => $top
			)
		);
		
		$airports_arr = array();
		
		foreach($airports as $airport)
		{
			$airports_arr[] = $airport['Log']['Origin'];
		}
		
		return $airports_arr;
	}

}
?>