<?php
class FlightsController extends AppController {
	var $name = 'Flights';
	var $uses = array();
	
	var $components = array('RequestHandler');
	
	function index()
	{
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Log =& ClassRegistry::init('Log');
		
		
		//get params
		$airline = "";
		if(isset($this->params['url']['airline']))
			$airline = $this->params['url']['airline'];
		
		$flight_num = "";
		if(isset($this->params['url']['flight_num']))
			$flight_num = $this->params['url']['flight_num'];
			
		$from = "";
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = "";
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
			
		$from_to = "";
		if(isset($this->params['url']['from_to']))
			$from_to = $this->params['url']['from_to'];
		
		$day = "";
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
		
		//redirect from_to
		if($from_to != '')
		{
			$arr = explode('_', $from_to);
			
			if(count($arr) != 2)
				$this->redirect('/');
				
			$from = $arr[0];
			$to = $arr[1];
			
			$this->redirect('/flights?airline='.$airline.'&flight_num='.$flight_num.'&from='.$from.'&to='.$to);
		}
		
		//get data
		if($airline != '' && $flight_num != '')
		{
			//load airline
			$airline_enum = $this->Enum->find('first',
				array(
					'conditions' => array(
						'Enum.code' => $airline,
						'Enum.category' => 'UNIQUE_CARRIERS'
					)
				)
			);
			
			//load flights
			$flights = array();
			
			$conditions = array(
				'Log.UniqueCarrier' => $airline,
				'Log.FlightNum' => $flight_num
			);
			
			if($from != '' && $to != '')
			{
				$conditions['Log.Origin'] = $from;
				$conditions['Log.Dest'] = $to;
			}
			
			if($day != '' && $day >=1 && $day <= 7)
			{
				$conditions['Log.DayOfWeek'] = $day;
			}
			
			$flights = $this->Log->find('all',
				array(
					'conditions' => $conditions
				)
			);
			
			$flights_by_airports = array();
			$flight_stats = array();
			$months = array();
			
			foreach($flights as $flight)
			{
				$date = $flight['Log']['Month'].'/1/'.$flight['Log']['Year'];
				$date_str = date('F, Y', strtotime($date));
				$months[$date_str] = '';
				
				$airports = $flight['Log']['Origin'].'-'.$flight['Log']['Dest'];
				
				if(!isset($flights_by_airports[$airports]))
				{
					$flights_by_airports[$airports] = array();
					
					$flight_stats[$airports] = array();
					$flight_stats[$airports]['total'] = 0;
					$flight_stats[$airports]['arrived'] = 0;
					$flight_stats[$airports]['cancelled'] = 0;
					$flight_stats[$airports]['diverted'] = 0;
					$flight_stats[$airports]['arrived_on_time'] = 0;
					$flight_stats[$airports]['avg_arrival_delay'] = 0;
					$flight_stats[$airports]['days'] = array();
					$flight_stats[$airports]['times'] = array();
				}
				
				$flights_by_airports[$airports][] = $flight;
				
				//arrival outcome
				$flight_stats[$airports]['total']++;
				
				if($flight['Log']['Cancelled'] == 1)
					$flight_stats[$airports]['cancelled']++;
				
				if($flight['Log']['Diverted'] == 1)
					$flight_stats[$airports]['diverted']++;
				
				if($flight['Log']['Diverted'] == 0 && $flight['Log']['Cancelled'] == 0)
				{
					$flight_stats[$airports]['arrived']++;
					$flight_stats[$airports]['avg_arrival_delay'] += $flight['Log']['ArrDelay'];
					
					if($flight['Log']['ArrDel15'] == 0)
						$flight_stats[$airports]['arrived_on_time']++;
				}
				
				//days
				$flight_day_index = $flight['Log']['DayOfWeek'];
				if(!isset($flight_stats[$airports]['days'][$flight_day_index]))
					$flight_stats[$airports]['days'][$flight_day_index] = array(
						'total' => 0,
						'delayed' => 0,
						'cancelled' => 0,
						'diverted' => 0
					);
				
				$flight_stats[$airports]['days'][$flight_day_index]['total']++;
				$flight_stats[$airports]['days'][$flight_day_index]['delayed'] += $flight['Log']['ArrDel15'];
				$flight_stats[$airports]['days'][$flight_day_index]['cancelled'] += $flight['Log']['Cancelled'];
				$flight_stats[$airports]['days'][$flight_day_index]['diverted'] += $flight['Log']['Diverted'];
				
				//times
				$time_block = $flight['Log']['DepTimeBlk'];
				if(!isset($flight_stats[$airports]['times'][$time_block]))
					$flight_stats[$airports]['times'][$time_block] = array(
						'total' => 0,
						'delayed' => 0,
						'cancelled' => 0,
						'diverted' => 0
					);

				$flight_stats[$airports]['times'][$time_block]['total']++;
				$flight_stats[$airports]['times'][$time_block]['delayed'] += $flight['Log']['ArrDel15'];
				$flight_stats[$airports]['times'][$time_block]['cancelled'] += $flight['Log']['Cancelled'];
				$flight_stats[$airports]['times'][$time_block]['diverted'] += $flight['Log']['Diverted'];
			}
			
			foreach($flight_stats as $airports => $stats)
			{
				ksort($flight_stats[$airports]['days']);
				ksort($flight_stats[$airports]['times']);
				
				$flight_stats[$airports]['avg_arrival_delay'] /= $flight_stats[$airports]['arrived'];
			}
			
			//set data for view
			$this->set('Airline', $airline);
			$this->set('FlightNum', $flight_num);
			$this->set('Day', $day);
			$this->set('From', $from);
			$this->set('To', $to);
			$this->set('AirlineInfo', $airline_enum);
			$this->set('AirportPairFlights', $flights_by_airports);
			$this->set('AirportPairStats', $flight_stats);
			$this->set('Months', $months);
		}
		elseif($airline != '')
		{
			$this->redirect('/airlines/'.$airline);
		}
	}

}
?>