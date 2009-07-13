<?php
class DisambiguateController extends AppController {
	var $name = 'Disambiguate';
	var $uses = array();
	var $components = array('Disambiguate');
	
	function index()
	{
		$this->redirect('/');
	}
	
	function airports()
	{
		$this->Enum =& ClassRegistry::init('Enum');
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
			$airports_used = $this->Disambiguate->GetAirportsUsed();
			
			$airports_from = $this->Disambiguate->GetAirports($from, $airports_used);
			
			$airports_to = $this->Disambiguate->GetAirports($to, $airports_used);
			
			if(count($airports_from) == 1 && count($airports_to) == 1)
			{
				$url = '/airports?from='.$airports_from[0]['Enum']['code'].'&to='.$airports_to[0]['Enum']['code'].'&day='.$day;
				$this->flash('Loading...', $url, 0);
			}
			else
			{
				$this->set('AirportsFrom', $airports_from);
				$this->set('AirportsTo', $airports_to);
				$this->set('From', $from);
				$this->set('To', $to);
				$this->set('Day', $day);
			}
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	function flights()
	{
		$this->Log =& ClassRegistry::init('Log');
		
		//get params
		$airline = "";
		if(isset($this->params['url']['airline']))
			$airline = $this->params['url']['airline'];
		
		$flight_num = "";
		if(isset($this->params['url']['flight_num']))
			$flight_num = $this->params['url']['flight_num'];
		
		$day = "";
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
		
		$from = "";
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = "";
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
			
		//get data
		if($airline != '' && $flight_num != '')
		{
			$conditions = array(
				'Log.UniqueCarrier' => $airline,
				'Log.FlightNum' => $flight_num
			);
			
			if($from != '' && $to != '')
			{
				$conditions['Log.Origin'] = $from;
				$conditions['Log.Dest'] = $to;
			}
			
			$flights = $this->Log->find('all',
				array(
					'conditions' => $conditions,
					'group' => array(
						'Log.Origin',
						'Log.Dest'
					)
				)
			);
			
			if(count($flights) == 1)
			{
				$url = '/flights?airline='.$airline.'&flight_num='.$flight_num.'&day='.$day.'&from='.$from.'&to='.$to;
				
				$this->flash('Loading...', $url, 0);
			}
			else
			{
				$this->set('Flights', $flights);
				$this->set('Airline', $airline);
				$this->set('FlightNum', $flight_num);
				$this->set('Day', $day);
				$this->set('From', $from);
				$this->set('To', $to);
			}
		}
		elseif($airline != '')
		{
			$url = '/airlines/'.$airline;
			$this->flash('Loading...', $url, 0);
		}
		else
		{
			$this->redirect('/');
		}
	}
	
}
?>