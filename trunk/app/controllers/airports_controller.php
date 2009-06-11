<?php
class AirportsController extends AppController {
	var $name = 'Airports';
	var $uses = array();
	
	function index()
	{
		$this->Log =& ClassRegistry::init('Log');

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
			$flights_from = $this->GetBestFlights($from, $to, $day);
			$flights_to = $this->GetBestFlights($to, $from, $day);
			
			$this->set('FlightsFrom', $flights_from);
			$this->set('FlightsTo', $flights_to);
			
			$this->set('From', $from);
			$this->set('To', $to);
			$this->set('Day', $day);
			
			//get months
			$months = array();
			
			foreach($flights_from as $flight)
			{
				$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
				$date_str = date('F, Y', strtotime($date));
				$months[$date_str] = '';
			}
			
			foreach($flights_to as $flight)
			{
				$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
				$date_str = date('F, Y', strtotime($date));
				$months[$date_str] = '';
			}
			
			$this->set('Months', $months);
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
}
?>