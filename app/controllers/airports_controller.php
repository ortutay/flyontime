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
			
			//flights
			$flights_from = $this->GetBestFlights($from, $to, $day);
			
			$this->set('FlightsFrom', $flights_from);
			
			//airlines
			$airlines_from = $this->GetBestAirlines($from, $to, $day);
			
			$this->set('AirlinesFrom', $airlines_from);

			$airline_names = $this->GetAirlineNames($airlines_from, array());
			
			$this->set('AirlineNames', $airline_names);
			
			//days
			$days_from = $this->GetDays($from, $to);
			
			$this->set('DaysFrom', $days_from);
			
			//times
			$times_from = $this->GetTimes($from, $to, $day);
			
			$this->set('TimesFrom', $times_from);
			
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
			'Log.Dest' => $to
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
					'(SUM(Log.ArrDelay) / (COUNT(Log.UniqueCarrier) - SUM(Log.Cancelled) - SUM(Log.Diverted))) as AvgArrDelay',
					'COUNT(Log.UniqueCarrier) as NumScheduled'
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
	
	private function GetDays($from, $to)
	{
		$days = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.DayOfWeek',
					'COUNT(Log.DayOfWeek) as NumScheduled',
					'SUM(Log.ArrDel15) as NumDelayed',
					'SUM(Log.Cancelled) as NumCancelled',
					'SUM(Log.Diverted) as NumDiverted'
				),
				'conditions' => array(
					'Log.Origin' => $from,
					'Log.Dest' => $to
				),
				'group' => array(
					'Log.DayOfWeek'
				),
				'order' => array(
					'Log.DayOfWeek ASC'
				)
			)
		);
		
		return $days;
	}
	
	private function GetTimes($from, $to, $day = '')
	{
		$conditions = array(
			'Log.Origin' => $from,
			'Log.Dest' => $to
		);
		
		if($day != '' && $day >=1 && $day <= 7)
		{
			$conditions['Log.DayOfWeek'] = $day;
		}
		
		$times = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.DepTimeBlk',
					'COUNT(Log.DepTimeBlk) as NumScheduled',
					'SUM(Log.ArrDel15) as NumDelayed',
					'SUM(Log.Cancelled) as NumCancelled',
					'SUM(Log.Diverted) as NumDiverted'
				),
				'conditions' => $conditions,
				'group' => array(
					'Log.DepTimeBlk'
				),
				'order' => array(
					'Log.DepTimeBlk ASC'
				)
			)
		);
		
		return $times;
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