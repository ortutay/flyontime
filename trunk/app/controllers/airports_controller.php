<?php
class AirportsController extends AppController {
	var $name = 'Airports';
	var $uses = array();
	
	var $components = array('RequestHandler');
	
	function index()
	{
		$this->Log =& ClassRegistry::init('Log');
		$this->Enum =& ClassRegistry::init('Enum');
		
		//get params
		$from = "";
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = "";
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
			
		$day = "";
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
			
		//get data
		if($from != '' && $to != '')
		{
			$this->set('From', $from);
			$this->set('To', $to);
			$this->set('Day', $day);
			
			$flights_from = $this->GetBestFlights($from, $to, $day);
			$flights_to = $this->GetBestFlights($to, $from, $day);
			
			$this->set('FlightsFrom', $flights_from);
			$this->set('FlightsTo', $flights_to);
			
			$airlines_from = $this->GetBestAirlines($from, $to, $day);
			$airlines_to = $this->GetBestAirlines($to, $from, $day);
			
			$this->set('AirlinesFrom', $airlines_from);
			$this->set('AirlinesTo', $airlines_to);

			$airline_names = $this->GetAirlineNames($airlines_from, $airlines_to);
			
			$this->set('AirlineNames', $airline_names);
			
			//get months and cities
			$months = array();
			$from_city = '';
			$to_city = '';
			
			foreach($flights_from as $flight)
			{
				$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
				$date_str = date('F, Y', strtotime($date));
				$months[$date_str] = '';
				
				$from_city = $flight['Log']['OriginCityName'];
				$to_city = $flight['Log']['DestCityName'];
			}
			
			foreach($flights_to as $flight)
			{
				$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
				$date_str = date('F, Y', strtotime($date));
				$months[$date_str] = '';
			}
			
			$this->set('Months', $months);
			
			$this->set('FromCity', $from_city);
			$this->set('ToCity', $to_city);
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	
	private function GetBestFlights($from, $to, $day = '')
	{
		$conditions = array(
			'Log.Origin' => $from,
			'Log.Dest' => $to,
			'Log.Cancelled' => 0,
			'Log.Diverted' => 0
		);
		
		if($day != '' && $day >=1 && $day <= 7)
		{
			$conditions['Log.DayOfWeek'] = $day;
		}
		
		$flights = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.UniqueCarrier',
					'Log.Carrier',
					'Log.FlightNum',
					'Log.Month',
					'Log.Year',
					'Log.OriginCityName',
					'Log.DestCityName',
					'AVG(Log.ArrDelay) as AvgArrDelay'
				),
				'conditions' => $conditions,
				'group' => array(
					'Log.UniqueCarrier',
					'Log.FlightNum'
				),
				'order' => array(
					'AvgArrDelay ASC'
				)
			)
		);
		
		return $flights;
	}
	
	private function GetBestAirlines($from, $to, $day = '')
	{
		$conditions = array(
			'Log.Origin' => $from,
			'Log.Dest' => $to
		);
		
		if($day != '' && $day >=1 && $day <= 7)
		{
			$conditions['Log.DayOfWeek'] = $day;
		}
		
		$airlines = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.UniqueCarrier',
					'Log.Carrier',
					'((1 - ((SUM(Log.Cancelled) + SUM(Log.Diverted) + SUM(Log.ArrDel15)) / COUNT(Log.UniqueCarrier)))*100) as PercentOnTime'
				),
				'conditions' => $conditions,
				'group' => array(
					'Log.UniqueCarrier'
				),
				'order' => array(
					'PercentOnTime DESC'
				)
			)
		);
		
		return $airlines;
	}
	
	private function GetAirlineNames($airlines1, $airlines2)
	{
		$unique_carriers = array();
		
		foreach($airlines1 as $airline)
		{
			$unique_carriers[] = $airline['Log']['UniqueCarrier'];
		}
		
		foreach($airlines2 as $airline)
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
			$fullname = $item['Enum']['description'];
			$abrev = '';
			
			if($fullname != '')
			{
				$words = explode(' ', $fullname);
				$abrev = $words[0];
				
				if(count($words) > 1)
				{
					if($words[1] != 'Airlines' && $words[1] != 'Air' && $words[1] != 'Inc.')
						$abrev .= ' '.$words[1];
				}
			}
			
			$airline_names[$item['Enum']['code']] = $abrev;
		}
		
		return $airline_names;
	}
}
?>